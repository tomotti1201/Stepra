<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('group_tasks')) {
            return;
        }

        if (!Schema::hasColumn('group_tasks', 'created_by')) {
            Schema::table('group_tasks', function (Blueprint $table) {
                $table->unsignedBigInteger('created_by')->nullable()->after('status');
            });

            try {
                Schema::table('group_tasks', function (Blueprint $table) {
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // ignore if foreign key cannot be added in current environment
            }
        }

        if (!Schema::hasColumn('group_tasks', 'created_at')) {
            Schema::table('group_tasks', function (Blueprint $table) {
                $table->timestamp('created_at')->nullable()->useCurrent()->after('created_by');
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('group_tasks')) {
            return;
        }

        Schema::table('group_tasks', function (Blueprint $table) {
            if (Schema::hasColumn('group_tasks', 'created_by')) {
                try {
                    $table->dropForeign(['created_by']);
                } catch (\Exception $e) {
                }
                $table->dropColumn('created_by');
            }

            if (Schema::hasColumn('group_tasks', 'created_at')) {
                $table->dropColumn('created_at');
            }
        });
    }
};
