<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // Ako želite odmah dodijeliti ulogu nekom korisniku

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Kreiranje Dozvola (Permissions)
        $permissions = [
            // Department Permissions
            'view departments',
            'create departments',
            'edit departments',
            'delete departments',

            // Product Permissions
            'view products',
            'create products',
            'edit products',
            'delete products',

            // ProductType Permissions
            'view product_types',
            'create product_types',
            'edit product_types',
            'delete product_types',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('Permissions created.');

        // Kreiranje Uloga (Roles) i dodjela dozvola

        // Uloga: Viewer
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);
        $viewerPermissions = [
            'view departments',
            'view products',
            'view product_types',
        ];
        $viewerRole->syncPermissions($viewerPermissions);
        $this->command->info('Viewer role created and permissions assigned.');

        // Uloga: Editor
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $editorPermissions = [
            // Sve što može viewer
            'view departments',
            'view products',
            'view product_types',
            // Plus create dozvole
            'create departments',
            'create products',
            'create product_types',
        ];
        $editorRole->syncPermissions($editorPermissions);
        $this->command->info('Editor role created and permissions assigned.');

        // Uloga: Admin - dobiva sve dozvole automatski putem Gate::before (definirat ćemo kasnije)
        // ili možete eksplicitno dodijeliti sve dozvole
        $adminRoleObject = Role::firstOrCreate(['name' => 'admin']);
        $allPermissionNames = Permission::pluck('name')->all(); // Dohvati imena svih dozvola iz baze
        $adminRoleObject->syncPermissions($allPermissionNames); // Dodijeli sve dozvole admin ulozi
        // $adminRole->syncPermissions(Permission::all()); // Opcija 1: Dodijeli sve trenutno postojeće dozvole
        // Opcija 2 (bolja): osloniti se na Gate::before za admina
        $this->command->info('Admin role created and ALL permissions assigned.');

$this->command->info('Ensuring admin user (admin@example.com) exists and has admin role...');

$adminUser = User::firstOrCreate(
    ['email' => 'admin@example.com'], // Uvjet za pronalazak ili kreiranje
    [                                 // Vrijednosti za kreiranje ako NE postoji
        'name' => 'Admin User',
        'password' => bcrypt('NekaJakaLozinka123!'), // OBAVEZNO PROMIJENITE OVO!
        'email_verified_at' => now(), // Opcionalno, da je odmah verificiran
    ]
);

if ($adminUser->wasRecentlyCreated) {
    $this->command->info('Admin user (admin@example.com) was CREATED.');
} else {
    $this->command->info('Admin user (admin@example.com) ALREADY EXISTED.');
}

// Dohvati admin ulogu (koja bi trebala biti kreirana ranije u ovom seederu)
$adminRoleToAssign = Role::where('name', 'admin')->first();

if ($adminRoleToAssign) {
    // Provjeri ima li korisnik već tu ulogu prije nego je pokušaš dodijeliti
    if (!$adminUser->hasRole($adminRoleToAssign)) {
        $adminUser->assignRole($adminRoleToAssign);
        $this->command->info('Role "'.$adminRoleToAssign->name.'" ASSIGNED to admin@example.com.');
    } else {
        $this->command->info('Admin user (admin@example.com) ALREADY HAS role "'.$adminRoleToAssign->name.'".');
    }
} else {
    $this->command->error('CRITICAL: Role "admin" NOT FOUND. Cannot assign to user.');
}

// Osvježi keš dozvola za ovog korisnika, za svaki slučaj
if ($adminUser) {
    $adminUser->forgetCachedPermissions();
}

        // Primjer: kreiranje editor korisnika
        if (User::where('email', 'editor@example.com')->doesntExist()) {
            $editorUser = User::factory()->create([
                'name' => 'Editor User',
                'email' => 'editor@example.com',
                'password' => bcrypt('password'), // Promijenite password!
            ]);
            $editorUser->assignRole('editor');
            $this->command->info('Editor user created and role assigned.');
        }

         // Primjer: kreiranje viewer korisnika
        if (User::where('email', 'viewer@example.com')->doesntExist()) {
            $viewerUser = User::factory()->create([
                'name' => 'Viewer User',
                'email' => 'viewer@example.com',
                'password' => bcrypt('password'), // Promijenite password!
            ]);
            $viewerUser->assignRole('viewer');
            $this->command->info('Viewer user created and role assigned.');
        }
         // Osigurajte da vaš 'test@example.com' korisnik ima neku ulogu ako ga koristite
        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser && !$testUser->hasAnyRole(Role::all())) {
             $testUser->assignRole('viewer'); // Dajte mu npr. viewer ulogu
             $this->command->info('Test user assigned viewer role.');
        }
    }
}