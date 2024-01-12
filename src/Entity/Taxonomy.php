<?php

namespace WpToolKit\Entity;

class Taxonomy
{
    public function __construct(
        public string $name,
        public string $labelName,
        public string $labelSingularName,
        public string $labelSearchItems = '',
        public string $labelAllItems = '',
        public string $labelParentItem = '',
        public string $labelParentItemColon = '',
        public string $labelEditItem = '',
        public string $labelUpdateItem = 'Update',
        public string $labelAddNewItem = 'Add',
        public string $labelNewItemName = '',
        public string $labelMenuName = '',
        public bool $hierarchical = false,
        public bool $showedUi = true,
        public bool $queryVar = true,
        public bool $showInMenu = true,
        public bool $showInNavMenus = true,
        public bool $showInQuickEdit = true,
        public bool $showTagCloud = true,
    ) {
        $this->updateLabelIfEmpty($this->labelSearchItems);
        $this->updateLabelIfEmpty($this->labelAllItems);
        $this->updateLabelIfEmpty($this->labelParentItem);
        $this->updateLabelIfEmpty($this->labelParentItemColon);
        $this->updateLabelIfEmpty($this->labelEditItem);
        $this->updateLabelIfEmpty($this->labelNewItemName);
        $this->updateLabelIfEmpty($this->labelMenuName);
    }

    /**
     * The function will update the label if the passed value is empty. 
     *
     * @param string &$label
     * @return void
     */
    public function updateLabelIfEmpty(string &$label): void
    {
        $label = empty($label) ? $this->labelName : $label;
    }

    public function getUrl(): string
    {
        return 'edit-tags.php?taxonomy=' . $this->name;
    }
}
