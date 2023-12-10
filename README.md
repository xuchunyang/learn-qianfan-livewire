# wire:stream + 文心一言

我开发了一个聊天机器人，使用了 Livewire 的 `wire:stream` 功能。这个机器人集成了百度的文心一言 API。

![](CleanShot%202023-12-11%20at%2000.06.42@2x.png)

## 用到主要工具

| 工具                  | 用途                                |
|---------------------|-----------------------------------|
| wire:stream         | 后端发送 SSE、前端渲染 SSE                 |
| symfony/http-client | 充当 SSE 客户端，解析来自百度 API 的 stream 返回 |
| laravel-markdown    | 从后端渲染 Markdown 为 HTML             |
