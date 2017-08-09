<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Collaborator;
use App\Models\Setting;

class CheckCollaboratorRankExtension extends Command
{
    protected $signature = 'check_collaborator_rank_extension';

    protected $description = 'Check collaborator condition to keep rank or down rank';

    public function handle()
    {
        $collaboratorRanks = Setting::getSettings(Setting::CATEGORY_COLLABORATOR_DB);

        $collaboratorRankDiamond = json_decode($collaboratorRanks[Setting::COLLABORATOR_DIAMOND]->value, true);

        $upRankTime = date('Y-m-d', strtotime('- ' . $collaboratorRankDiamond[Collaborator::RERANK_TIME_ATTRIBUTE] . ' months'));

        $collaborators = Collaborator::select('collaborator.id', 'collaborator.user_id', 'collaborator.rank_id', 'collaborator.current_revenue', 'collaborator.upranked_at', 'collaborator.create_discount_percent', 'collaborator.commission_percent')
            ->join('setting', 'collaborator.rank_id', '=', 'setting.id')
            ->where('setting.code', Setting::COLLABORATOR_DIAMOND)
            ->where('collaborator.upranked_at', '<=', $upRankTime)
            ->get();

        $collaboratorRankSilver = json_decode($collaboratorRanks[Setting::COLLABORATOR_SILVER]->value, true);
        $collaboratorRankGold = json_decode($collaboratorRanks[Setting::COLLABORATOR_GOLD]->value, true);

        foreach($collaborators as $collaborator)
        {
            if($collaborator->current_revenue < $collaboratorRankSilver[Collaborator::REVENUE_ATTRIBUTE])
            {
                $collaborator->rank_id = $collaboratorRanks[Setting::COLLABORATOR_SILVER]->id;
                $collaborator->create_discount_percent = $collaboratorRankSilver[Collaborator::DISCOUNT_ATTRIBUTE];
                $collaborator->commission_percent = $collaboratorRankSilver[Collaborator::COMMISSION_ATTRIBUTE];
                $collaborator->upranked_at = date('Y-m-d H:i:s');
            }
            else if($collaborator->current_revenue < $collaboratorRankGold[Collaborator::REVENUE_ATTRIBUTE])
            {
                $collaborator->rank_id = $collaboratorRanks[Setting::COLLABORATOR_GOLD]->id;
                $collaborator->create_discount_percent = $collaboratorRankGold[Collaborator::DISCOUNT_ATTRIBUTE];
                $collaborator->commission_percent = $collaboratorRankGold[Collaborator::COMMISSION_ATTRIBUTE];
                $collaborator->upranked_at = date('Y-m-d H:i:s');
            }
            else
            {
                $collaborator->current_revenue -= $collaboratorRankGold[Collaborator::REVENUE_ATTRIBUTE];
                $collaborator->upranked_at = date('Y-m-d H:i:s');
            }

            $collaborator->save();
        }
    }
}