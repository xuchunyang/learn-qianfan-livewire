<?php

namespace App\Livewire;

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
    }

    #[Title('livewire:wire + 文心一言')]
    public function render()
    {
        return view('livewire.qianwen');
    }
}
