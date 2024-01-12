<?php

namespace WpToolKit\Entity;

class Taxonomy
{
    public function __construct(
        private string $name,
        private string $labelName,
        private string $labelSingularName,
        private string $labelSearchItems = '',
        private string $labelAllItems = '',
        private string $labelParentItem = '',
        private string $labelParentItemColon = '',
        private string $labelEditItem = '',
        private string $labelUpdateItem = 'Update',
        private string $labelAddNewItem = 'Add',
        private string $labelNewItemName = '',
        private string $labelMenuName = '',
        private bool $hierarchical = false,
        private bool $showedUi = true,
        private bool $queryVar = true,
        private bool $showInMenu = true,
        private bool $showInNavMenus = true,
        private bool $showInQuickEdit = true,
        private bool $showTagCloud = true,
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLabelName(): string
    {
        return $this->labelName;
    }

    public function setLabelName(string $labelName): void
    {
        $this->labelName = $labelName;
    }

    public function getLabelSingularName(): string
    {
        return $this->labelSingularName;
    }

    public function setLabelSingularName(string $labelSingularName): void
    {
        $this->labelSingularName = $labelSingularName;
    }

    public function getLabelSearchItems(): string
    {
        return $this->labelSearchItems;
    }

    public function setLabelSearchItems(string $labelSearchItems): void
    {
        $this->labelSearchItems = $labelSearchItems;
    }

    public function getLabelAllItems(): string
    {
        return $this->labelAllItems;
    }

    public function setLabelAllItems(string $labelAllItems): void
    {
        $this->labelAllItems = $labelAllItems;
    }

    public function getLabelParentItem(): string
    {
        return $this->labelParentItem;
    }

    public function setLabelParentItem(string $labelParentItem): void
    {
        $this->labelParentItem = $labelParentItem;
    }

    public function getLabelParentItemColon(): string
    {
        return $this->labelParentItemColon;
    }

    public function setLabelParentItemColon(string $labelParentItemColon): void
    {
        $this->labelParentItemColon = $labelParentItemColon;
    }

    public function getLabelEditItem(): string
    {
        return $this->labelEditItem;
    }

    public function setLabelEditItem(string $labelEditItem): void
    {
        $this->labelEditItem = $labelEditItem;
    }

    public function getLabelUpdateItem(): string
    {
        return $this->labelUpdateItem;
    }

    public function setLabelUpdateItem(string $labelUpdateItem): void
    {
        $this->labelUpdateItem = $labelUpdateItem;
    }

    public function getLabelAddNewItem(): string
    {
        return $this->labelAddNewItem;
    }

    public function setLabelAddNewItem(string $labelAddNewItem): void
    {
        $this->labelAddNewItem = $labelAddNewItem;
    }

    public function getLabelNewItemName(): string
    {
        return $this->labelNewItemName;
    }

    public function setLabelNewItemName(string $labelNewItemName): void
    {
        $this->labelNewItemName = $labelNewItemName;
    }

    public function getLabelMenuName(): string
    {
        return $this->labelMenuName;
    }

    public function setLabelMenuName(string $labelMenuName): void
    {
        $this->labelMenuName = $labelMenuName;
    }

    public function isHierarchical(): bool
    {
        return $this->hierarchical;
    }

    public function setHierarchical(bool $hierarchical): void
    {
        $this->hierarchical = $hierarchical;
    }

    public function isShowedUi(): bool
    {
        return $this->showedUi;
    }

    public function setShowedUi(bool $showedUi): void
    {
        $this->showedUi = $showedUi;
    }

    public function isQueryVar(): bool
    {
        return $this->queryVar;
    }

    public function setQueryVar(bool $queryVar): void
    {
        $this->queryVar = $queryVar;
    }

    public function isShowInMenu(): bool
    {
        return $this->showInMenu;
    }

    public function setShowInMenu(bool $showInMenu): void
    {
        $this->showInMenu = $showInMenu;
    }

    public function isShowInNavMenus(): bool
    {
        return $this->showInNavMenus;
    }

    public function setShowInNavMenus(bool $showInNavMenus): void
    {
        $this->showInNavMenus = $showInNavMenus;
    }

    public function isShowInQuickEdit(): bool
    {
        return $this->showInQuickEdit;
    }

    public function setShowInQuickEdit(bool $showInQuickEdit): void
    {
        $this->showInQuickEdit = $showInQuickEdit;
    }

    public function isShowTagCloud(): bool
    {
        return $this->showTagCloud;
    }

    public function setShowTagCloud(bool $showTagCloud): void
    {
        $this->showTagCloud = $showTagCloud;
    }

    public function getUrl(): string
    {
        return 'edit-tags.php?taxonomy=' . $this->getName();
    }
}
