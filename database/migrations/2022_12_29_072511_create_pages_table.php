<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the 'pages' table already exists
        if (!Schema::hasTable('pages')) {
            // Create the table if it doesn't exist
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->fullText();
                $table->text('description')->nullable();
                $table->string('slug')->nullable();
                $table->longText('settings')->nullable();
                $table->timestamps();
            });
        } else {
            // If the table exists, check for each column and add if missing
            Schema::table('pages', function (Blueprint $table) {
                if (!Schema::hasColumn('pages', 'id')) {
                    $table->id();
                }

                if (!Schema::hasColumn('pages', 'name')) {
                    $table->string('name')->fullText();
                }

                if (!Schema::hasColumn('pages', 'description')) {
                    $table->text('description')->nullable();
                }

                if (!Schema::hasColumn('pages', 'slug')) {
                    $table->string('slug')->nullable();
                }

                if (!Schema::hasColumn('pages', 'settings')) {
                    $table->longText('settings')->nullable();
                }
                
                if (!Schema::hasColumn('pages', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('pages');
    }
};
