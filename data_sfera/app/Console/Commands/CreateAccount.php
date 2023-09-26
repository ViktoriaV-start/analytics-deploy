<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Company;
use App\Models\Room;
use App\Models\Service;
use App\Models\Token;
use App\Models\Type;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\DB;

class CreateAccount extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:account
                            {company}
                            {api}
                            {api_key}
                           ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $company = $this->argument('company');
        $api = $this->argument('api');
        $api_key = $this->argument('api_key');
        $api_type = $this->choice('What is the type of token?', ['api-key', 'api-service', 'company']);

        $this->storeToDB([
            'company'  => $company,
            'api'      => $api,
            'api_type' => $api_type,
            'api_key'  => $api_key
        ]);
    }

    /**
     * Check if tuples exist in tables or create new tuples.
     */
    private function storeToDb($obj)
    {
        $newCompany = Company::firstOrCreate([
            'name' => strtolower(trim($obj['company']))
        ]);
        $this->showInfo($newCompany->id, $obj['company']);

        $newService = Service::firstOrCreate([
            'service' => $obj['api']
        ]);
        $this->showInfo($newService->id, 'service');

        $newAccount = null;
        // Check if account exists
        $account = DB::table('accounts')->where('company_id', $newCompany->id)->where('service_id', $newService->id)->first();

        if(isset($account->id)) {
            $this->alert('Account already exists');
            die();
        } else {
            $newAccount = (new Account)->create([
                'company_id' => $newCompany->id,
                'service_id' => $newService->id
            ]);

            $this->showInfo($newAccount->id, 'account');
        }

        $newType = Type::where('type',$obj['api_type'])->first();
        $this->showInfo($newType->id, $obj['api_type']);


        $token = DB::table('tokens')->where('service_id', $newService->id)->where('account_id', $newAccount->id)->get();
        if(!isset($token->id)) {
            $newToken = (new Token())->create([
                'service_id' => $newService->id,
                'type_id' => $newType->id,
                'account_id' => $newAccount->id,
                'service_key' => $obj['api_key'],
            ]);
        }
        $this->showInfo($newToken->id, 'token');
    }

    private function showInfo(int $id, string $item)
    {
        if($id) {
            $this->info("Confirmation: $item is in database");
        } else {
            $this->info("Error in $item: check and try later");
            die();
        };
    }

}
