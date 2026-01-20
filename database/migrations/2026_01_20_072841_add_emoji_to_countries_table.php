<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->string('emoji', 10)->nullable()->after('name');
        });

        // Populate emoji flags based on iso2
        $countries = DB::table('countries')->get();
        foreach ($countries as $country) {
            $iso2 = strtoupper($country->iso2);
            if (strlen($iso2) === 2) {
                $emoji = mb_convert_encoding('&#' . (ord($iso2[0]) + 127397) . ';', 'UTF-8', 'HTML-ENTITIES');
                $emoji .= mb_convert_encoding('&#' . (ord($iso2[1]) + 127397) . ';', 'UTF-8', 'HTML-ENTITIES');
                DB::table('countries')->where('id', $country->id)->update(['emoji' => $emoji]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('emoji');
        });
    }
};
