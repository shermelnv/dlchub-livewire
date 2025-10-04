<flux:modal name="archive-students" class="md:w-[40rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Archive All Students</flux:heading>
            <flux:text class="mt-2">
                The semester has ended. Are you sure you want to archive <strong>all students</strong>? 
                This action will mark all current students as archived and cannot be undone.
            </flux:text>
        </div>

        <div class="flex justify-end space-x-2 pt-4">
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button wire:click="archiveUser" variant="danger">
                Archive All Students
            </flux:button>
        </div>
    </div>
</flux:modal>
