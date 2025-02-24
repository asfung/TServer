<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Resource;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ResourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $adminRole = Role::create(['name' => 'Admin']);
        // $userRole = Role::create(['name' => 'User']);
        
        // $access = Resource::create([
        //     'key' => 'resource-access',
        //     'name' => 'Access'
        // ]);

        // $post = Resource::create([
        //     'key' => 'resource-post',
        //     'name' => 'Post'
        // ]);

        // $bookmark = Resource::create(['name' => 'Bookmark']);
        // $notification = Resource::create(['name' => 'Notification']);
        // $profileSetting = Resource::create(['name' => 'Profile Setting']);
    
        // $accessToggle = Permission::create([
        //     'key' => 'access-toggle',
        //     'name' => 'Access Toggle Enable/Disable',
        //     'endpoint' => 'access.toggle',
        //     'resource_id' => $access->id
        // ]);

        // $postCreate = Permission::create([
        //     'key' => 'post-create',
        //     'name' => 'Creating Post',
        //     'endpoint' => 'post.create',
        //     'resource_id' => $post->id
        // ]);
    
        $permission = Permission::create([
            'key' => 'resources-permission-create',
            'name' => 'Create Permission',
            'endpoint' => 'pemissions.create',
            'resource_id' => 1
        ]);

        // $adminRole->resources()->attach($access->id);
        // $adminRole->permissions()->attach($accessToggle->id);

        // $adminRole->resources()->attach($access->id);
        // $adminRole->permissions()->attach($accessToggle->id);
    }
}
