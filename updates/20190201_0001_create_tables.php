<?php

declare(strict_types=1);

namespace Vdlp\RedirectConditions\Updates;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

class CreateTables extends Migration
{
    public function up(): void
    {
        Schema::create('vdlp_redirectconditions_condition_parameters', static function (Blueprint $table): void {
            // Table configuration
            $table->engine = 'InnoDB';

            // Columns
            $table->increments('id');
            $table->unsignedInteger('redirect_id');
            $table->dateTime('is_enabled')->nullable();
            $table->string('condition_code');
            $table->text('parameters')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('redirect_id', 'vdlp_redirectconditions_redirect')
                ->references('id')
                ->on('vdlp_redirect_redirects')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Indices
            $table->unique([
                'redirect_id',
                'condition_code',
            ], 'vdlp_redirectconditions_unique');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('vdlp_redirectconditions_condition_parameters');
        Schema::enableForeignKeyConstraints();
    }
}
