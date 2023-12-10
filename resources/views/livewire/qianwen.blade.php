<div>
    <div class="max-w-[720px] mx-auto px-4 pt-8 pb-24 overflow-auto">
        @if(!count($messages) && !$question)
            <div class="mt-[15vh]">
                <div class="flex justify-center items-center">
                    <x-livewire-logo class="hidden dark:block"/>
                    <x-livewire-logo-light class="dark:hidden"/>
                    <span class="text-2xl mx-4">+</span>
                    <img class="w-[160px]" src="/yiyan-logo.png" alt="文心一言">
                </div>
                <p class="text-center mt-4 text-lg">
                    我开发了一个聊天机器人，使用了 Livewire 的 <code
                        class="dark:bg-slate-950 inline-block px-1.5 rounded">wire:stream</code> 功能。这个机器人集成了百度的文心一言
                    API。
                </p>
            </div>
        @endif

        <div>
            @foreach($messages as $message)
                @if($message['role'] === 'user')
                    <div>
                        <div class="font-bold font-mono mr-2 select-none float-left">
                            <span class="text-green-400">➜</span>
                            <span class="text-cyan-400 font-light">~</span>
                        </div>
                        <div>{{ $message['content'] }}</div>
                    </div>
                @else
                    <div
                        class="prose dark:prose-invert max-w-none prose-p:my-[0.5em] prose-pre:my-[0.5em] prose-li:my-[0.15em] prose-ol:my-[0.5em] prose-ul:my-[0.5em]">
                        <div class="mt-1.5 mb-4">
                            <x-markdown theme="github-dark">{!! $message['content'] !!}</x-markdown>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if($question)
            <div>
                <div class="font-bold font-mono mr-2 select-none float-left">
                    <span class="text-green-400">➜</span>
                    <span class="text-cyan-400 font-light">~</span>
                </div>
                <div>{{ $question }}</div>
            </div>
            <pre class="mt-1.5 mb-4 whitespace-pre-wrap" wire:stream="answer"></pre>
        @endif

        <form class="fixed inset-x-0 bottom-0 bg-white/30 dark:bg-slate-950/30 backdrop-blur"
              wire:submit="submitPrompt">
            <div class="max-w-[720px] mx-auto px-4 pt-4 pb-8">
                <div class="flex">
                    <label class="sr-only" for="prompt">开始对话</label>
                    <textarea id="prompt"
                              wire:model="prompt"
                              autofocus
                              rows="1"
                              class="rounded-md flex-1 dark:bg-slate-900 dark:border-slate-700 shadow-md min-h-[42px] max-h-screen"
                              placeholder="开始对话"></textarea>
                    <x-primary-button>发送</x-primary-button>
                </div>
            </div>
        </form>
    </div>
</div>
