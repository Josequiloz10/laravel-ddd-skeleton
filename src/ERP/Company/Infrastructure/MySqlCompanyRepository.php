<?php

declare(strict_types=1);

namespace Medine\ERP\Company\Infrastructure;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Medine\ERP\Company\Domain\Company;
use Medine\ERP\Company\Domain\CompanyRepository;
use Medine\ERP\Company\Domain\ValueObjects\CompanyAddress;
use Medine\ERP\Company\Domain\ValueObjects\CompanyCreatedAt;
use Medine\ERP\Company\Domain\ValueObjects\CompanyId;
use Medine\ERP\Company\Domain\ValueObjects\CompanyLogo;
use Medine\ERP\Company\Domain\ValueObjects\CompanyName;
use Medine\ERP\Company\Domain\ValueObjects\CompanyState;
use Medine\ERP\Company\Domain\ValueObjects\CompanyUpdatedAt;

final class MySqlCompanyRepository implements CompanyRepository
{

    public function save(Company $company): void
    {
        DB::table('companies')->insert([
            'id' => $company->id()->value(),
            'name' => $company->name()->value(),
            'address' => $company->address()->value(),
            'status' => $company->state()->value(),
            'logo' => $company->logo()->value(),
            'created_at' => $company->createdAt()->value(),
            'updated_at' => $company->updatedAt()->value()
        ]);
    }

    public function update(Company $company): void
    {
        DB::table('companies')->where('companies.id', $company->id()->value())->take(1)->update([
            'name' => $company->name()->value(),
            'address' => $company->address()->value(),
            'status' => $company->state()->value(),
            'logo' => $company->logo()->value(),
            'updated_at' => $company->updatedAt()->value(),
        ]);
    }

    public function find(CompanyId $id): ?Company
    {
        $row = DB::table('companies')->where('companies.id', $id->value())->first();

        return !empty($row) ? Company::fromDatabase(
            new CompanyId($row->id),
            new CompanyName($row->name),
            new CompanyAddress($row->address),
            new CompanyState($row->status),
            new CompanyLogo($row->logo),
            new CompanyCreatedAt($row->created_at),
            new CompanyUpdatedAt($row->updated_at)
        ) : null;
    }

}
