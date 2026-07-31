<?php

namespace App\Filament\Resources\MonitoringSurveyResource\Pages;

use App\Filament\Resources\MonitoringSurveyResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CreateMonitoringSurvey extends CreateRecord
{
    protected static string $resource = MonitoringSurveyResource::class;

    public function getTitle(): string 
    {
        return 'Form Pembuatan SK Kegiatan / Survei';
    }

    protected ?string $selectedMonth = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        $this->selectedMonth = strtolower($data['bulan']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl(
            'index',
            [
                'activeTab' => $this->selectedMonth,
            ]
        );
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data survei berhasil ditambahkan');
    }

    public function mount(): void
    {
        parent::mount();

        session([
            'activeTab' => request()->query('activeTab'),
        ]);
    }
}
