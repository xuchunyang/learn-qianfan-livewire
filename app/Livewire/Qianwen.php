<?php

namespace App\Livewire;

use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Title;
use Livewire\Component;

class Qianwen extends Component
{
    public $prompt = '';

    public $question = '';

    public $answer = '';

    public $messages = [];

    public function submitPrompt()
    {
        $this->question = $this->prompt;

        $this->prompt = '';

        $this->js('$wire.ask()');
    }

    public function ask(\App\Services\Qianwen $qianwen)
    {
        $executed = RateLimiter::attempt(
            'qianwen:ask'.request()->ip(),
            $perThreeHour = 20,
            function () use ($qianwen) {
                $this->messages[] = [
                    'role' => 'user',
                    'content' => $this->question,
                ];
                $this->answer = $qianwen->ask($this->messages, function ($partial) {
                    $this->stream(
                        to: 'answer',
                        content: $partial,
                    );
                }, 'completions_pro');
                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => $this->answer,
                ];
                $this->question = '';
                $this->answer = '';
            },
            $decayRate = 3 * 3600,
        );

        if (! $executed) {
            $this->js('alert("您的提问过于频繁，请稍后再试。")');
        }
    }

    #[Title('livewire:wire + 文心一言')]
    public function render()
    {
        return view('livewire.qianwen');
    }
}
