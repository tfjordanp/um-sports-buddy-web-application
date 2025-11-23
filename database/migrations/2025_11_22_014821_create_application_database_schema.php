<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $databaseName = env('DB_DATABASE');

        //DB::statement("CREATE DATABASE IF NOT EXISTS $databaseName;"); unneccesary in sqlite
        Schema::create('Users', function (Blueprint $table) {
            $table->id();
            // Username: Chosen name displayed in the app
            $table->string('name')->unique();

            // Email: User's email address (for registration and login)
            $table->string('email')->unique();

            // Password: Encrypted password for account security
            $table->string('password');
            
            // Location: Geographic location of the user (city/area)
            $table->foreignId('location_id')->nullable()->constrained('Cities')->onDelete('set null');

            // Profile Picture: Optional image representing the user (store file path/URL)
            $table->string('profile_picture_url')->nullable();

            $table->rememberToken();
           
            $table->timestamps();
        });
        
        try{
            Schema::create('Countries', function (Blueprint $table) {
                $table->id();
                $table->string('name');
            });

            Schema::create('States', function (Blueprint $table) {
                $table->id();
                $table->string('name');

                $table->foreignId('country_id')->constrained()->onDelete('cascade');
            });

            Schema::create('Cities', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->float('longitude')->nullable();
                $table->float('latitude')->nullable();

                $table->foreignId('state_id')->constrained()->onDelete('cascade');
            });
        }
        catch(Exception $e){

        }

        Schema::create('Sports', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('description');
            $table->string('profile_picture_url')->nullable();

            //popularity, computed property
            
            $table->timestamps();
        });
        Schema::create('Events', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('description');
            $table->string('location_details');

            $table->foreignId('sport_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('Cities')->cascadeOnDelete();
            $table->timestamp('scheduled_date_time');
            $table->foreignId('organizer_id')->constrained('Users','id')->cascadeOnDelete();
            $table->integer('max_participants');
            $table->string('profile_picture_url')->nullable();
            
            $table->timestamps();
        });

        Schema::create('UserSportPreferences', function (Blueprint $table) {
            $table->id();

            //$table->enum('level',['beginner','amateur','semi-pro','pro']);
            $table->integer('level',false,true);

            $table->foreignId('sport_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            
            $table->timestamps();
        });

        Schema::create('UserEventApplication', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->timestamps();
        });

        Schema::create('UserUserConnections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user1_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user2_id')->constrained('users')->cascadeOnDelete();
            
            $table->timestamps();
        });

        Schema::create('Admin', function (Blueprint $table) {
            $table->id();
            // Username: Chosen name displayed in the app
            $table->string('username')->unique();

            // Email: User's email address (for registration and login)
            $table->string('email')->unique();

            // Password: Encrypted password for account security
            $table->string('password');
            
            $table->rememberToken();
           
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $databaseName = env('DB_DATABASE');

        Schema::dropIfExists('UserSportPreferences');
        Schema::dropIfExists('UserUserConnections');
        Schema::dropIfExists('Events');
        
        Schema::dropIfExists('Users');
        Schema::dropIfExists('Sports');
        /*Schema::dropIfExists('Countries');
        Schema::dropIfExists('Locations');
        */


        Schema::dropIfExists('Admin');
        Schema::dropIfExists('sessions');

        //DB::statement("DROP DATABASE IF EXISTS $databaseName;");
    }
};
