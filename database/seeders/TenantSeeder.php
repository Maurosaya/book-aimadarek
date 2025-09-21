<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Resource;
use App\Models\Location;
use App\Models\Customer;
use App\Models\AvailabilityRule;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default location
        $location = Location::create([
            'tenant_id' => tenancy()->tenant->id,
            'name' => ['es' => 'Ubicación Principal', 'en' => 'Main Location', 'nl' => 'Hoofdlocatie'],
            'address' => 'Dirección de ejemplo',
            'timezone' => tenancy()->tenant->timezone,
            'active' => true,
        ]);

        // Create default services based on tenant type
        $this->createDefaultServices($location);

        // Create default resources
        $this->createDefaultResources($location);

        // Create availability rules
        $this->createAvailabilityRules();

        // Create demo customer
        Customer::create([
            'tenant_id' => tenancy()->tenant->id,
            'name' => 'Cliente Demo',
            'email' => 'demo@example.com',
            'phone' => '+34 600 000 000',
        ]);
    }

    private function createDefaultServices(Location $location)
    {
        $tenantId = tenancy()->tenant->id;
        
        // Default services for different business types
        $services = [
            // Restaurant services
            'restaurante' => [
                'name' => ['es' => 'Reserva de Mesa', 'en' => 'Table Reservation', 'nl' => 'Tafelreservering'],
                'duration_min' => 120,
                'buffer_before_min' => 15,
                'buffer_after_min' => 15,
                'required_resource_types' => ['TABLE'],
            ],
            // Barbershop services
            'barberia' => [
                'name' => ['es' => 'Corte de Cabello', 'en' => 'Haircut', 'nl' => 'Haarsnit'],
                'duration_min' => 30,
                'buffer_before_min' => 5,
                'buffer_after_min' => 5,
                'required_resource_types' => ['STAFF'],
            ],
            // Beauty salon services
            'belleza' => [
                'name' => ['es' => 'Manicura', 'en' => 'Manicure', 'nl' => 'Manicure'],
                'duration_min' => 45,
                'buffer_before_min' => 10,
                'buffer_after_min' => 10,
                'required_resource_types' => ['STAFF'],
            ],
            // Dental services
            'dental' => [
                'name' => ['es' => 'Consulta Dental', 'en' => 'Dental Consultation', 'nl' => 'Tandheelkundig Consult'],
                'duration_min' => 30,
                'buffer_before_min' => 10,
                'buffer_after_min' => 15,
                'required_resource_types' => ['STAFF', 'ROOM'],
            ],
        ];

        // Create appropriate service based on tenant ID
        foreach ($services as $type => $serviceData) {
            if (str_contains($tenantId, $type) || $type === 'restaurante') {
                Service::create([
                    'tenant_id' => $tenantId,
                    'name' => $serviceData['name'],
                    'duration_min' => $serviceData['duration_min'],
                    'buffer_before_min' => $serviceData['buffer_before_min'],
                    'buffer_after_min' => $serviceData['buffer_after_min'],
                    'required_resource_types' => $serviceData['required_resource_types'],
                    'active' => true,
                ]);
                break; // Only create one service per tenant
            }
        }
    }

    private function createDefaultResources(Location $location)
    {
        $tenantId = tenancy()->tenant->id;

        // Create resources based on business type
        if (str_contains($tenantId, 'ranch')) {
            // Restaurant resources
            Resource::create([
                'tenant_id' => $tenantId,
                'location_id' => $location->id,
                'type' => 'TABLE',
                'label' => ['es' => 'Mesa 1', 'en' => 'Table 1', 'nl' => 'Tafel 1'],
                'capacity' => 4,
                'active' => true,
            ]);

            Resource::create([
                'tenant_id' => $tenantId,
                'location_id' => $location->id,
                'type' => 'TABLE',
                'label' => ['es' => 'Mesa 2', 'en' => 'Table 2', 'nl' => 'Tafel 2'],
                'capacity' => 2,
                'active' => true,
            ]);
        } elseif (str_contains($tenantId, 'beerta') || str_contains($tenantId, 'glow')) {
            // Barbershop/Beauty resources
            Resource::create([
                'tenant_id' => $tenantId,
                'location_id' => $location->id,
                'type' => 'STAFF',
                'label' => ['es' => 'Profesional 1', 'en' => 'Professional 1', 'nl' => 'Professional 1'],
                'capacity' => 1,
                'active' => true,
            ]);
        } elseif (str_contains($tenantId, 'smile')) {
            // Dental resources
            Resource::create([
                'tenant_id' => $tenantId,
                'location_id' => $location->id,
                'type' => 'STAFF',
                'label' => ['es' => 'Dr. Demo', 'en' => 'Dr. Demo', 'nl' => 'Dr. Demo'],
                'capacity' => 1,
                'active' => true,
            ]);

            Resource::create([
                'tenant_id' => $tenantId,
                'location_id' => $location->id,
                'type' => 'ROOM',
                'label' => ['es' => 'Consultorio 1', 'en' => 'Consultation Room 1', 'nl' => 'Consultatiekamer 1'],
                'capacity' => 1,
                'active' => true,
            ]);
        }
    }

    private function createAvailabilityRules()
    {
        $tenantId = tenancy()->tenant->id;

        // Create basic weekly availability (Monday to Friday, 9 AM to 6 PM)
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        
        foreach ($days as $day) {
            AvailabilityRule::create([
                'tenant_id' => $tenantId,
                'day_of_week' => $day,
                'start_time' => '09:00',
                'end_time' => '18:00',
                'capacity' => 10,
                'active' => true,
            ]);
        }

        // Saturday (9 AM to 2 PM)
        AvailabilityRule::create([
            'tenant_id' => $tenantId,
            'day_of_week' => 'saturday',
            'start_time' => '09:00',
            'end_time' => '14:00',
            'capacity' => 5,
            'active' => true,
        ]);
    }
}
