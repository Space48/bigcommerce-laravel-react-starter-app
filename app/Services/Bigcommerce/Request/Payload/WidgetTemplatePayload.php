<?php

namespace App\Services\Bigcommerce\Request\Payload;

class WidgetTemplatePayload
{
    public function __construct(
        private string $name,
        private string $template,
        private string $appIdentifier,
        private string $widgetId,
        private array $sectionsScema,
        private int $channelId,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'template' => $this->template,
            'schema' => [
                $this->getAppIdSchema(),
                $this->getAppConfigSchema()
            ],
            'channel_id' => $this->channelId,
        ];
    }

    private function getAppIdSchema(): array
    {
        return [
            'type' => 'hidden',
            'settings' => [
                [
                    'id' => 'app',
                    'default' => $this->appIdentifier
                ],
                [
                    'id' => 'app-id',
                    'default' => $this->widgetId,
                ]
            ]
        ];
    }

    private function getAppConfigSchema(): array
    {
        return [
            'type' => 'tab',
            'label' => 'Configuration',
            'sections' => $this->sectionsScema,
        ];
    }
}
