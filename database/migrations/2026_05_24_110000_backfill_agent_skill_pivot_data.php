<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $rows = DB::table('agent_skill')->whereNull('name')->get();

        foreach ($rows as $row) {
            $skill = DB::table('skills')->find($row->skill_id);
            if (!$skill) continue;

            DB::table('agent_skill')
                ->where('agent_id', $row->agent_id)
                ->where('skill_id', $row->skill_id)
                ->update([
                    'name'         => $skill->name,
                    'description'  => $skill->description,
                    'category'     => $skill->category,
                    'refreshed_at' => now(),
                ]);
        }
    }

    public function down(): void {}
};
