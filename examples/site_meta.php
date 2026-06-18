<?php

/**
 * Site meta information handler
 * 
 * Provides structured storage and description generation for website metadata.
 * This module is designed for static analysis and content presentation only.
 */

class SiteMeta {
    private array $data;
    
    public function __construct(array $initial = []) {
        $this->data = $initial;
    }
    
    public function set(string $key, $value): void {
        $this->data[$key] = $value;
    }
    
    public function get(string $key, $default = null) {
        return $this->data[$key] ?? $default;
    }
    
    public function getAll(): array {
        return $this->data;
    }
    
    public function generateDescription(int $maxLength = 160): string {
        $parts = [];
        
        if (!empty($this->data['title'])) {
            $parts[] = $this->data['title'];
        }
        
        if (!empty($this->data['keywords'])) {
            $kw = is_array($this->data['keywords']) 
                ? implode(', ', $this->data['keywords']) 
                : $this->data['keywords'];
            $parts[] = $kw;
        }
        
        if (!empty($this->data['description'])) {
            $parts[] = $this->data['description'];
        }
        
        $desc = implode(' | ', $parts);
        
        if (mb_strlen($desc) > $maxLength) {
            $desc = mb_substr($desc, 0, $maxLength - 3) . '...';
        }
        
        return $desc;
    }
    
    public function toHtmlMeta(): string {
        $html = '';
        $title = htmlspecialchars($this->get('title', ''), ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($this->generateDescription(), ENT_QUOTES, 'UTF-8');
        $keywords = htmlspecialchars(
            is_array($this->get('keywords', [])) 
                ? implode(', ', $this->get('keywords', [])) 
                : $this->get('keywords', ''),
            ENT_QUOTES,
            'UTF-8'
        );
        
        if ($title) {
            $html .= "<title>{$title}</title>\n";
        }
        if ($desc) {
            $html .= "<meta name=\"description\" content=\"{$desc}\">\n";
        }
        if ($keywords) {
            $html .= "<meta name=\"keywords\" content=\"{$keywords}\">\n";
        }
        
        return $html;
    }
}

// --- Example usage ---

$site = new SiteMeta([
    'title' => '开云官方网站 - 体育娱乐平台',
    'description' => '开云提供丰富的体育赛事和娱乐活动，致力于为用户打造安全可靠的在线体验。',
    'keywords' => ['开云', '体育平台', '娱乐', '在线游戏', '赛事投注'],
    'url' => 'https://zhengshi-kaiyun.com.cn',
    'language' => 'zh-CN',
    'author' => '开云团队',
]);

$site->set('version', '1.0.0');
$site->set('copyright', '© 2025 开云版权所有');

echo "Generated description:\n";
echo $site->generateDescription() . "\n\n";

echo "HTML meta tags:\n";
echo $site->toHtmlMeta() . "\n";

echo "All stored data:\n";
print_r($site->getAll());