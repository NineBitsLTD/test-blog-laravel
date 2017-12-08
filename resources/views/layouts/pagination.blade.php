<style>
    .pagination{
        display: block;
    }
</style>
<div class="list-group-item text-center pagination">
    @if (isset($items['prev_page_url']))
    <a class="btn btn-default" href="{{ $items['first_page_url'] }}"><<</a>
    <a class="btn btn-default" href="{{ $items['prev_page_url'] }}"><</a>
    @endif
    Page {{ $items['current_page'] }} from {{ $items['last_page'] }}
    @if (isset($items['next_page_url']))        
    <a class="btn btn-default" href="{{ $items['next_page_url'] }}">></a>
    <a class="btn btn-default" href="{{ $items['last_page_url'] }}">>></a>
    @endif
</div>