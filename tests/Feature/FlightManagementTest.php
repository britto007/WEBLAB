<?php

use App\Models\Airline;
use App\Models\Airport;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;

function createTestFlight(array $overrides = []): Flight
{
    $airline = Airline::create(['name' => 'Test Airways', 'code' => 'TA']);
    $from = Airport::create(['name' => 'Dhaka Airport', 'code' => 'DAC', 'city' => 'Dhaka', 'country' => 'BD']);
    $to = Airport::create(['name' => 'Dubai Airport', 'code' => 'DXB', 'city' => 'Dubai', 'country' => 'AE']);

    return Flight::create(array_merge([
        'flight_number' => 'TA-101',
        'airline_id' => $airline->id,
        'departure_airport_id' => $from->id,
        'arrival_airport_id' => $to->id,
        'departure_time' => now()->addDay(),
        'arrival_time' => now()->addDay()->addHours(4),
        'price' => 5000,
        'total_seats' => 10,
        'available_seats' => 10,
        'status' => 'scheduled',
    ], $overrides));
}

test('new registrations always receive the user role', function () {
    $this->post('/register', [
        'name' => 'Passenger',
        'email' => 'passenger@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect('/dashboard');

    expect(User::where('email', 'passenger@example.com')->value('role'))->toBe('user');
});

test('passengers cannot access admin pages', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin/dashboard')
        ->assertForbidden();
});

test('admin can access the admin dashboard', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']))
        ->get('/admin/dashboard')
        ->assertOk();
});

test('admin accounts cannot access passenger routes', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get('/my-bookings')
        ->assertForbidden();
});

test('admin login redirects to the admin dashboard', function () {
    User::factory()->create([
        'email' => 'admin-login@example.com',
        'password' => 'password',
        'role' => 'admin',
    ]);

    $this->post('/login', [
        'email' => 'admin-login@example.com',
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));
});

test('booking decreases available seats and calculates total', function () {
    $user = User::factory()->create();
    $flight = createTestFlight();

    $this->actingAs($user)->post('/bookings', [
        'flight_id' => $flight->id,
        'seats_booked' => 2,
    ])->assertRedirect('/my-bookings');

    expect($flight->fresh()->available_seats)->toBe(8);
    $this->assertDatabaseHas('bookings', [
        'user_id' => $user->id,
        'flight_id' => $flight->id,
        'seats_booked' => 2,
        'total_price' => 10000,
        'status' => 'confirmed',
    ]);
});

test('a passenger cannot cancel another passenger booking', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $flight = createTestFlight();
    $booking = Booking::factory()->create([
        'user_id' => $owner->id,
        'flight_id' => $flight->id,
    ]);

    $this->actingAs($otherUser)
        ->patch(route('bookings.cancel', $booking))
        ->assertForbidden();
});

test('cancelling a booking restores seats', function () {
    $user = User::factory()->create();
    $flight = createTestFlight();
    $booking = Booking::factory()->create([
        'user_id' => $user->id,
        'flight_id' => $flight->id,
        'seats_booked' => 1,
    ]);

    $this->actingAs($user)
        ->patch(route('bookings.cancel', $booking))
        ->assertRedirect();

    expect($booking->fresh()->status)->toBe('cancelled');
    expect($flight->fresh()->available_seats)->toBe(10);
});

test('flight seat availability labels match the remaining seats', function () {
    $flight = createTestFlight(['available_seats' => 20])->fresh();
    expect($flight->seatAvailabilityLabel())->toBe('Available');

    $flight->available_seats = 5;
    expect($flight->seatAvailabilityLabel())->toBe('Few seats left');

    $flight->available_seats = 0;
    expect($flight->seatAvailabilityLabel())->toBe('Sold out');
    expect($flight->seatAvailabilityClass())->toBe('seat-status--sold-out');
});

test('admin dashboard ranks favourite routes by confirmed booking count', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $passenger = User::factory()->create();
    $otherPassenger = User::factory()->create();
    $flight = createTestFlight();

    Booking::factory()->create(['user_id' => $passenger->id, 'flight_id' => $flight->id]);
    Booking::factory()->create(['user_id' => $otherPassenger->id, 'flight_id' => $flight->id]);
    Booking::factory()->create([
        'user_id' => $otherPassenger->id,
        'flight_id' => $flight->id,
        'status' => 'cancelled',
    ]);

    $secondFrom = Airport::create([
        'name' => 'Chattogram Airport',
        'code' => 'CGP',
        'city' => 'Chattogram',
        'country' => 'BD',
    ]);
    $secondTo = Airport::create([
        'name' => 'Sylhet Airport',
        'code' => 'ZYL',
        'city' => 'Sylhet',
        'country' => 'BD',
    ]);
    $secondFlight = Flight::create([
        'flight_number' => 'TA-102',
        'airline_id' => $flight->airline_id,
        'departure_airport_id' => $secondFrom->id,
        'arrival_airport_id' => $secondTo->id,
        'departure_time' => now()->addDays(2),
        'arrival_time' => now()->addDays(2)->addHour(),
        'price' => 4000,
        'total_seats' => 10,
        'available_seats' => 10,
        'status' => 'scheduled',
    ]);
    Booking::factory()->create([
        'user_id' => $passenger->id,
        'flight_id' => $secondFlight->id,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Favourite Routes Report')
        ->assertSee('DAC')
        ->assertSee('DXB')
        ->assertSee('2 bookings')
        ->assertSeeInOrder(['DAC → DXB', 'CGP → ZYL']);
});

test('admin can view and search the registered users directory', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $passenger = User::factory()->create([
        'name' => 'Directory Passenger',
        'email' => 'directory@example.com',
        'phone' => '01712345678',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.users.index', ['search' => 'directory@example.com']))
        ->assertOk()
        ->assertSee((string) $passenger->id)
        ->assertSee('Directory Passenger')
        ->assertSee('directory@example.com')
        ->assertSee('01712345678');
});

test('passengers cannot view the registered users directory', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.users.index'))
        ->assertForbidden();
});
