<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Location;
use App\Models\Resource;
use App\Models\AvailabilityRule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting demo seed process...');
        
        // Create demo tenants with complete data
        $tenants = $this->createDemoTenants();
        
        // Generate access cards
        $this->generateAccessCards($tenants);
        
        $this->command->info('✅ Demo seed completed successfully!');
    }
    
    /**
     * Create all demo tenants with complete data
     */
    private function createDemoTenants(): array
    {
        $tenants = [];
        
        // 1. Ranch Restaurant
        $tenants[] = $this->createRanchTenant();
        
        // 2. Beerta Barbers
        $tenants[] = $this->createBeertaBarbersTenant();
        
        // 3. Glow Beauty
        $tenants[] = $this->createGlowBeautyTenant();
        
        // 4. Smile Dental
        $tenants[] = $this->createSmileDentalTenant();
        
        return $tenants;
    }
    
    /**
     * Create Ranch Restaurant tenant
     */
    private function createRanchTenant(): array
    {
        $this->command->info('🍽️ Creating Ranch Restaurant tenant...');
        
        // Create tenant
        $tenant = Tenant::create([
            'id' => 'ranch',
            'brand_name' => 'Ranch Restaurant',
            'default_locale' => 'es',
            'supported_locales' => ['es', 'en', 'nl'],
            'timezone' => 'Europe/Madrid',
            'settings' => [
                'business_type' => 'restaurant',
                'max_advance_days' => 30,
                'min_advance_hours' => 2,
            ],
        ]);
        
        // Initialize tenant context
        tenancy()->initialize($tenant);
        
        // Create location
        $location = Location::create([
            'name' => 'Ranch Restaurant',
            'address' => 'Calle Mayor 123, Madrid, España',
            'timezone' => 'Europe/Madrid',
            'active' => true,
        ]);
        
        // Create tables (6 tables: 2/4/6 pax combinables)
        $tables = [];
        for ($i = 1; $i <= 6; $i++) {
            $capacity = $i <= 2 ? 2 : ($i <= 4 ? 4 : 6);
            $tables[] = Resource::create([
                'location_id' => $location->id,
                'type' => Resource::TYPE_TABLE,
                'label' => [
                    'es' => "Mesa {$i}",
                    'en' => "Table {$i}",
                    'nl' => "Tafel {$i}",
                ],
                'capacity' => $capacity,
                'combinable_with' => ['TABLE'],
                'active' => true,
            ]);
        }
        
        // Create services with translations
        $lunchService = Service::create([
            'tenant_id' => $tenant->id,
            'name' => [
                'es' => 'Almuerzo 60',
                'en' => 'Lunch 60',
                'nl' => 'Lunch 60',
            ],
            'duration_min' => 60,
            'buffer_before_min' => 5,
            'buffer_after_min' => 15,
            'price_cents' => 2500, // €25.00
            'required_resource_types' => ['TABLE'],
            'active' => true,
        ]);
        
        $dinnerService = Service::create([
            'tenant_id' => $tenant->id,
            'name' => [
                'es' => 'Cena 90',
                'en' => 'Dinner 90',
                'nl' => 'Diner 90',
            ],
            'duration_min' => 90,
            'buffer_before_min' => 5,
            'buffer_after_min' => 15,
            'price_cents' => 3500, // €35.00
            'required_resource_types' => ['TABLE'],
            'active' => true,
        ]);
        
        // Create availability rules (12-15 and 18-22)
        $this->createAvailabilityRules($location, [
            ['start' => '12:00', 'end' => '15:00'], // Lunch
            ['start' => '18:00', 'end' => '22:00'], // Dinner
        ]);
        
        // Create owner user and token
        $owner = $this->createOwnerUser($tenant, 'ranch@demo.com');
        $token = $owner->createToken('demo');
        
        // Create sample bookings
        $this->createSampleBookings($tenant, $location, $tables, [$lunchService, $dinnerService]);
        
        return [
            'tenant' => $tenant,
            'owner' => $owner,
            'token' => $token,
            'service_id' => $lunchService->id,
        ];
    }
    
    /**
     * Create Beerta Barbers tenant
     */
    private function createBeertaBarbersTenant(): array
    {
        $this->command->info('💈 Creating Beerta Barbers tenant...');
        
        // Create tenant
        $tenant = Tenant::create([
            'id' => 'beerta-barbers',
            'brand_name' => 'Beerta Barbers',
            'default_locale' => 'en',
            'supported_locales' => ['en', 'es', 'nl'],
            'timezone' => 'Europe/Amsterdam',
            'settings' => [
                'business_type' => 'barbershop',
                'max_advance_days' => 14,
                'min_advance_hours' => 1,
            ],
        ]);
        
        // Initialize tenant context
        tenancy()->initialize($tenant);
        
        // Create location
        $location = Location::create([
            'name' => 'Beerta Barbers',
            'address' => 'Damrak 45, Amsterdam, Netherlands',
            'timezone' => 'Europe/Amsterdam',
            'active' => true,
        ]);
        
        // Create 3 staff members
        $staff = [];
        for ($i = 1; $i <= 3; $i++) {
            $staff[] = Resource::create([
                'location_id' => $location->id,
                'type' => Resource::TYPE_STAFF,
                'label' => [
                    'es' => "Barbero {$i}",
                    'en' => "Barber {$i}",
                    'nl' => "Kapper {$i}",
                ],
                'capacity' => 1,
                'combinable_with' => [],
                'active' => true,
            ]);
        }
        
        // Create services
        $haircutService = Service::create([
            'tenant_id' => $tenant->id,
            'name' => [
                'es' => 'Corte 30',
                'en' => 'Haircut 30',
                'nl' => 'Knippen 30',
            ],
            'duration_min' => 30,
            'buffer_before_min' => 5,
            'buffer_after_min' => 10,
            'price_cents' => 2500, // €25.00
            'required_resource_types' => ['STAFF'],
            'active' => true,
        ]);
        
        $beardService = Service::create([
            'tenant_id' => $tenant->id,
            'name' => [
                'es' => 'Barba 20',
                'en' => 'Beard 20',
                'nl' => 'Baard 20',
            ],
            'duration_min' => 20,
            'buffer_before_min' => 5,
            'buffer_after_min' => 10,
            'price_cents' => 1500, // €15.00
            'required_resource_types' => ['STAFF'],
            'active' => true,
        ]);
        
        // Create availability rules (10-18)
        $this->createAvailabilityRules($location, [
            ['start' => '10:00', 'end' => '18:00'],
        ]);
        
        // Create owner user and token
        $owner = $this->createOwnerUser($tenant, 'beerta@demo.com');
        $token = $owner->createToken('demo');
        
        // Create sample bookings
        $this->createSampleBookings($tenant, $location, $staff, [$haircutService, $beardService]);
        
        return [
            'tenant' => $tenant,
            'owner' => $owner,
            'token' => $token,
            'service_id' => $haircutService->id,
        ];
    }
    
    /**
     * Create Glow Beauty tenant
     */
    private function createGlowBeautyTenant(): array
    {
        $this->command->info('💅 Creating Glow Beauty tenant...');
        
        // Create tenant
        $tenant = Tenant::create([
            'id' => 'glow-beauty',
            'brand_name' => 'Glow Beauty',
            'default_locale' => 'en',
            'supported_locales' => ['en', 'es', 'nl'],
            'timezone' => 'Europe/Brussels',
            'settings' => [
                'business_type' => 'beauty_salon',
                'max_advance_days' => 21,
                'min_advance_hours' => 2,
            ],
        ]);
        
        // Initialize tenant context
        tenancy()->initialize($tenant);
        
        // Create location
        $location = Location::create([
            'name' => 'Glow Beauty',
            'address' => 'Rue de la Paix 78, Brussels, Belgium',
            'timezone' => 'Europe/Brussels',
            'active' => true,
        ]);
        
        // Create 3 staff members
        $staff = [];
        for ($i = 1; $i <= 3; $i++) {
            $staff[] = Resource::create([
                'location_id' => $location->id,
                'type' => Resource::TYPE_STAFF,
                'label' => [
                    'es' => "Esteticista {$i}",
                    'en' => "Beautician {$i}",
                    'nl' => "Schoonheidsspecialist {$i}",
                ],
                'capacity' => 1,
                'combinable_with' => [],
                'active' => true,
            ]);
        }
        
        // Create services
        $manicureService = Service::create([
            'tenant_id' => $tenant->id,
            'name' => [
                'es' => 'Manicura 45',
                'en' => 'Manicure 45',
                'nl' => 'Manicure 45',
            ],
            'duration_min' => 45,
            'buffer_before_min' => 10,
            'buffer_after_min' => 15,
            'price_cents' => 3000, // €30.00
            'required_resource_types' => ['STAFF'],
            'active' => true,
        ]);
        
        $pedicureService = Service::create([
            'tenant_id' => $tenant->id,
            'name' => [
                'es' => 'Pedicura 60',
                'en' => 'Pedicure 60',
                'nl' => 'Pedicure 60',
            ],
            'duration_min' => 60,
            'buffer_before_min' => 10,
            'buffer_after_min' => 15,
            'price_cents' => 4000, // €40.00
            'required_resource_types' => ['STAFF'],
            'active' => true,
        ]);
        
        // Create availability rules (9-19)
        $this->createAvailabilityRules($location, [
            ['start' => '09:00', 'end' => '19:00'],
        ]);
        
        // Create owner user and token
        $owner = $this->createOwnerUser($tenant, 'glow@demo.com');
        $token = $owner->createToken('demo');
        
        // Create sample bookings
        $this->createSampleBookings($tenant, $location, $staff, [$manicureService, $pedicureService]);
        
        return [
            'tenant' => $tenant,
            'owner' => $owner,
            'token' => $token,
            'service_id' => $manicureService->id,
        ];
    }
    
    /**
     * Create Smile Dental tenant
     */
    private function createSmileDentalTenant(): array
    {
        $this->command->info('🦷 Creating Smile Dental tenant...');
        
        // Create tenant
        $tenant = Tenant::create([
            'id' => 'smile-dental',
            'brand_name' => 'Smile Dental',
            'default_locale' => 'en',
            'supported_locales' => ['en', 'es', 'nl'],
            'timezone' => 'Europe/London',
            'settings' => [
                'business_type' => 'dental_clinic',
                'max_advance_days' => 60,
                'min_advance_hours' => 24,
            ],
        ]);
        
        // Initialize tenant context
        tenancy()->initialize($tenant);
        
        // Create location
        $location = Location::create([
            'name' => 'Smile Dental Clinic',
            'address' => 'Baker Street 221B, London, UK',
            'timezone' => 'Europe/London',
            'active' => true,
        ]);
        
        // Create 2 doctors (staff) and 2 rooms
        $doctors = [];
        for ($i = 1; $i <= 2; $i++) {
            $doctors[] = Resource::create([
                'location_id' => $location->id,
                'type' => Resource::TYPE_STAFF,
                'label' => [
                    'es' => "Dr. {$i}",
                    'en' => "Dr. {$i}",
                    'nl' => "Dr. {$i}",
                ],
                'capacity' => 1,
                'combinable_with' => ['ROOM'],
                'active' => true,
            ]);
        }
        
        $rooms = [];
        for ($i = 1; $i <= 2; $i++) {
            $rooms[] = Resource::create([
                'location_id' => $location->id,
                'type' => Resource::TYPE_ROOM,
                'label' => [
                    'es' => "Sala {$i}",
                    'en' => "Room {$i}",
                    'nl' => "Kamer {$i}",
                ],
                'capacity' => 1,
                'combinable_with' => ['STAFF'],
                'active' => true,
            ]);
        }
        
        // Create services
        $cleaningService = Service::create([
            'tenant_id' => $tenant->id,
            'name' => [
                'es' => 'Limpieza 45',
                'en' => 'Cleaning 45',
                'nl' => 'Reiniging 45',
            ],
            'duration_min' => 45,
            'buffer_before_min' => 15,
            'buffer_after_min' => 15,
            'price_cents' => 8000, // €80.00
            'required_resource_types' => ['STAFF', 'ROOM'],
            'active' => true,
        ]);
        
        $rootCanalService = Service::create([
            'tenant_id' => $tenant->id,
            'name' => [
                'es' => 'Endodoncia 90',
                'en' => 'Root Canal 90',
                'nl' => 'Wortelkanaalbehandeling 90',
            ],
            'duration_min' => 90,
            'buffer_before_min' => 15,
            'buffer_after_min' => 15,
            'price_cents' => 15000, // €150.00
            'required_resource_types' => ['STAFF', 'ROOM'],
            'active' => true,
        ]);
        
        // Create availability rules (8-17)
        $this->createAvailabilityRules($location, [
            ['start' => '08:00', 'end' => '17:00'],
        ]);
        
        // Create owner user and token
        $owner = $this->createOwnerUser($tenant, 'smile@demo.com');
        $token = $owner->createToken('demo');
        
        // Create sample bookings
        $allResources = array_merge($doctors, $rooms);
        $this->createSampleBookings($tenant, $location, $allResources, [$cleaningService, $rootCanalService]);
        
        return [
            'tenant' => $tenant,
            'owner' => $owner,
            'token' => $token,
            'service_id' => $cleaningService->id,
        ];
    }
    
    /**
     * Create availability rules for a location
     */
    private function createAvailabilityRules(Location $location, array $timeSlots): void
    {
        $daysOfWeek = [1, 2, 3, 4, 5]; // Monday to Friday
        
        foreach ($daysOfWeek as $day) {
            foreach ($timeSlots as $slot) {
                AvailabilityRule::create([
                    'tenant_id' => tenant('id'),
                    'location_id' => $location->id,
                    'day_of_week' => $day,
                    'start_time' => $slot['start'],
                    'end_time' => $slot['end'],
                    'exceptions' => [],
                    'max_covers_slot' => null,
                    'active' => true,
                ]);
            }
        }
    }
    
    /**
     * Create owner user for a tenant
     */
    private function createOwnerUser(Tenant $tenant, string $email): User
    {
        return User::create([
            'name' => 'Demo Owner',
            'email' => $email,
            'password' => Hash::make('Demo!1234'),
            'email_verified_at' => now(),
        ]);
    }
    
    /**
     * Create sample bookings for today and tomorrow
     */
    private function createSampleBookings(Tenant $tenant, Location $location, array $resources, array $services): void
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        
        // Create customers
        $customers = [];
        for ($i = 1; $i <= 3; $i++) {
            $customers[] = Customer::create([
                'tenant_id' => $tenant->id,
                'name' => "Cliente Demo {$i}",
                'email' => "cliente{$i}@demo.com",
                'phone' => "+3412345678{$i}",
                'gdpr_optin' => true,
            ]);
        }
        
        // Create bookings for today
        $this->createBookingForDate($tenant, $services[0], $resources[0], $customers[0], $today, '14:00');
        $this->createBookingForDate($tenant, $services[1] ?? $services[0], $resources[1] ?? $resources[0], $customers[1], $today, '16:00');
        
        // Create bookings for tomorrow
        $this->createBookingForDate($tenant, $services[0], $resources[0], $customers[2], $tomorrow, '10:00');
        $this->createBookingForDate($tenant, $services[1] ?? $services[0], $resources[1] ?? $resources[0], $customers[0], $tomorrow, '15:00');
    }
    
    /**
     * Create a booking for a specific date and time
     */
    private function createBookingForDate(Tenant $tenant, Service $service, Resource $resource, Customer $customer, Carbon $date, string $time): void
    {
        $startAt = $date->copy()->setTimeFromTimeString($time);
        $endAt = $startAt->copy()->addMinutes($service->duration_min);
        
        $booking = Booking::create([
            'tenant_id' => $tenant->id,
            'service_id' => $service->id,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'party_size' => $resource->type === Resource::TYPE_TABLE ? $resource->capacity : null,
            'status' => Booking::STATUS_CONFIRMED,
            'source' => 'demo',
            'notes' => 'Reserva de demostración',
            'customer_id' => $customer->id,
        ]);
        
        // Allocate resource to booking
        $booking->resources()->attach($resource->id);
    }
    
    /**
     * Generate access cards for all tenants
     */
    private function generateAccessCards(array $tenants): void
    {
        $accessCards = [];
        
        foreach ($tenants as $tenantData) {
            $accessCards[] = [
                'subdomain' => $tenantData['tenant']->id,
                'brand_name' => $tenantData['tenant']->brand_name,
                'credentials' => [
                    'email' => $tenantData['owner']->email,
                    'password' => 'Demo!1234',
                ],
                'service_id' => $tenantData['service_id'],
                'api_token' => $tenantData['token']->plainTextToken,
                'panel_url' => "https://{$tenantData['tenant']->id}.book.aimadarek.com/panel",
                'widget_url' => "https://{$tenantData['tenant']->id}.book.aimadarek.com",
            ];
        }
        
        // Save to storage
        Storage::put('demo_access.json', json_encode($accessCards, JSON_PRETTY_PRINT));
        
        $this->command->info('📋 Access cards generated in storage/app/demo_access.json');
    }
}
