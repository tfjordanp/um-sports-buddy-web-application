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
        Schema::create('User', function (Blueprint $table) {
            $table->id();
            // Username: Chosen name displayed in the app
            $table->string('username')->unique();

            // Email: User's email address (for registration and login)
            $table->string('email')->unique();

            // Password: Encrypted password for account security
            $table->string('password');
            
            // Location: Geographic location of the user (city/area)
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');

            // Profile Picture: Optional image representing the user (store file path/URL)
            $table->string('profile_picture_url')->nullable();

            $table->rememberToken();
           
            $table->timestamps();
        });
        Schema::create('Country', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('Location', function (Blueprint $table) {
            $table->id();
            $table->string('location');
            $table->float('longitude')->nullable();
            $table->float('latitude')->nullable();

            $table->foreignId('country_id')->constrained()->onDelete('cascade');
        });
        Schema::create('Sport', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('description');
            $table->string('profile_picture_url')->nullable();

            //popularity, computed property
            
            $table->timestamps();
        });
        Schema::create('Event', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('description');

            $table->foreignId('sport_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->timestamp('scheduled_date_time');
            $table->foreignId('organize_id')->constrained('user','user_id')->cascadeOnDelete();
            $table->integer('max_participants');
            $table->string('profile_picture_url')->nullable();
            
            $table->timestamps();
        });

        Schema::create('UserSportPreferences', function (Blueprint $table) {
            $table->id();

            $table->enum('level',['beginner','amateur','semi-pro','pro']);

            $table->foreignId('sport_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            
            $table->timestamps();
        });

        Schema::create('UserUserConnections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user1_id')->constrained('user')->cascadeOnDelete();
            $table->foreignId('user2_id')->constrained('user')->cascadeOnDelete();
            
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $databaseName = env('DB_DATABASE');

        Schema::dropIfExists('User');
        Schema::dropIfExists('Country');
        Schema::dropIfExists('Location');
        Schema::dropIfExists('Sport');
        Schema::dropIfExists('Event');
        Schema::dropIfExists('UserSportPreferences');
        Schema::dropIfExists('UserUserConnections');
        Schema::dropIfExists('Admin');

        //DB::statement("DROP DATABASE IF EXISTS $databaseName;");
    }
};
