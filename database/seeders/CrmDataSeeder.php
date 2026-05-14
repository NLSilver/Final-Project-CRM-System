<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Customer, Lead, Activity, FollowUp, User};
use Illuminate\Support\Str;

class CrmDataSeeder extends Seeder
{
    public function run(): void
    {
        $allUsers = User::all();
        $admin = User::where('role', 'admin')->first();

        foreach ($allUsers as $user) {
            for ($i = 1; $i <= 4; $i++) {
                $fName = fake()->firstName();
                $lName = fake()->lastName();
                
                $customer = Customer::create([
                    'first_name'       => $fName,
                    'last_name'        => $lName,
                    'email'            => strtolower($fName . "." . $lName . rand(1, 99) . "@gmail.com"),
                    'phone'            => '09' . rand(111111111, 999999999),
                    'company_name'     => fake()->company() . " Inc.",
                    'address'          => fake()->address(),
                    'status'           => fake()->randomElement(['Active', 'Inactive']),
                    'assigned_user_id' => $user->id,
                ]);

                
                for ($j = 1; $j <= 2; $j++) {
                    Activity::create([
                        'user_id'       => $user->id,
                        'customer_id'   => $customer->id,
                        'activity_type' => fake()->randomElement(['call', 'email', 'meeting']),
                        'description'   => "Discussed " . fake()->sentence() . " with " . $fName,
                        'activity_date' => now()->subDays(rand(1, 15)),
                    ]);

                    FollowUp::create([
                        'user_id'     => $user->id,
                        'customer_id' => $customer->id,
                        'title'       => "Initial Outreach: " . $fName,
                        'description' => "Verify " . fake()->word() . " requirements.",
                        'due_date'    => now()->addDays(rand(1, 7)),
                        'status'      => 'pending',
                    ]);

                    FollowUp::create([
                        'user_id'     => $user->id,
                        'customer_id'     => $customer->id,
                        'title'       => "Requirements Gathering",
                        'description' => "Completed the " . fake()->word() . " phase.",
                        'due_date'    => now()->subDays(rand(1, 5)),
                        'status'      => 'completed',
                    ]);
                }
            }

            for ($i = 1; $i <= 3; $i++) {
                $businessName = fake()->company();
                
                $lead = Lead::create([
                    'name'             => $businessName,
                    'email'            => strtolower(Str::slug($businessName) . "@gmail.com"),
                    'phone'            => '09' . rand(111111111, 999999999),
                    'source'           => fake()->randomElement(['Website', 'LinkedIn', 'Referral']),
                    'status'           => fake()->randomElement(['New', 'Contacted', 'Qualified', 'Negotiation']),
                    'priority'         => fake()->randomElement(['Low', 'Medium', 'High']),
                    'expected_value'   => rand(5000, 100000),
                    'notes'            => "Lead generated via " . fake()->word(),
                    'assigned_user_id' => $user->id,
                ]);

                for ($j = 1; $j <= 2; $j++) {
                    Activity::create([
                        'user_id'       => $user->id,
                        'lead_id'       => $lead->id,
                        'activity_type' => fake()->randomElement(['call', 'email', 'meeting']),
                        'description'   => "Discussed " . fake()->sentence() . " with " . $businessName,
                        'activity_date' => now()->subDays(rand(1, 15)),
                    ]);
                }

                FollowUp::create([
                    'user_id'     => $user->id,
                    'lead_id'     => $lead->id,
                    'title'       => "Initial Outreach: " . $businessName,
                    'description' => "Verify " . fake()->word() . " requirements.",
                    'due_date'    => now()->addDays(rand(1, 7)),
                    'status'      => 'pending',
                ]);

                FollowUp::create([
                    'user_id'     => $user->id,
                    'lead_id'     => $lead->id,
                    'title'       => "Requirements Gathering",
                    'description' => "Completed the " . fake()->word() . " phase.",
                    'due_date'    => now()->subDays(rand(1, 5)),
                    'status'      => 'completed',
                ]);
            }
        }
    }
}