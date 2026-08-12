<?php

namespace App\Filament\Resources\MonitoringSurveyResource\Pages;

use App\Filament\Resources\MonitoringSurveyResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMonitoringSurvey extends CreateRecord
{
    protected static string $resource = MonitoringSurveyResource::class;

    protected ?string $selectedMonth = null;

    public function getTitle(): string 
    {
        return 'Form Pembuatan SK Kegiatan / Survei';
    }

    public function mount(): void
    {
        parent::mount();

        session([
            'activeTab' => request()->query('activeTab'),
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        if (isset($data['bulan'])) {
            $this->selectedMonth = strtolower($data['bulan']);
        }

        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data berhasil ditambahkan');
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl(
            'index',
            array_filter([
                'activeTab' => $this->selectedMonth,
            ])
        );
    }
}