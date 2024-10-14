<?php

namespace Millat\Pagify;

abstract class Block
{
    /**
     * The block ID.
     *
     * @var string
     */
    protected $id = null;
    
    /**
     * The block name.
     *
     * @var string
     */
    protected $name = null;
    
    /**
     * The block icon.
     *
     * @var string
     */
    protected $icon = null;
    
    /**
     * The block image.
     *
     * @var string
     */
    protected $image = null;
    
    /**
     * The block tab.
     *
     * @var string
     */
    protected $tab = null;

    /**
     * The block view.
     *
     * @var string
     */
    protected $view = null;
    
    /**
     * The block settings.
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Get the block ID. If not defined, derive it from the class name.
     *
     * @return void
     */
    protected function setBlockInfo(): void
    {
        $this->settings['id'] = $this->id ?: $this->convertClassNameToId(get_class($this));
        $this->settings['name'] = $this->name ?: __(ucwords(str_replace('-', ' ', $this->settings['id'])));
        $this->settings['icon'] = $this->icon ?: '<i class="icon-clipboard"></i>';
        $this->settings['image'] = $this->image ?: '';
        $this->settings['view'] = $this->view ?: 'blocks.' . $this->settings['id'];
        $this->settings['tab'] = $this->tab ?: __(ucwords(str_replace('-', ' ', $this->settings['id'])));
    }

    /**
     * Helper method to convert class name to snake-case or kebab-case ID.
     *
     * @param string $className
     * @return string
     */
    protected function convertClassNameToId(string $className): string
    {
        $className = (new \ReflectionClass($className))->getShortName();
        // Convert PascalCase to kebab-case (or use snake_case)
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $className));
    }

    /**
     * Return the default block settings, can be overridden in child classes.
     *
     * @return array
     */
    public function getSettings(): array
    {
        $this->setBlockInfo();
        $this->settings['fields'] = $this->collectAddMethods();

        return $this->settings;
    }

    /**
     * Automatically collect all methods in the child class with the "add" prefix
     * and gather their returned arrays into one array.
     *
     * @return array
     */
    protected function collectAddMethods(): array
    {
        $collected = [];
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            // Check if the method starts with "add"
            if (strpos($method->getName(), 'add') === 0) {
                $result = $method->invoke($this);
                if (is_array($result)) {
                    $collected[] = $result;
                }
            }
        }

        return $collected;
    }
}
