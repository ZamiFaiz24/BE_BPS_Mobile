{{-- resources/views/admin/datasets/partials/table-and-pagination.blade.php --}}
<div class="overflow-x-auto">
    @include('admin.datasets.partials.table', ['datasets' => $datasets])
</div>
<div class="p-6 border-t border-gray-200">
    {{ $datasets->links('vendor.pagination.tailwind') }}
</div>