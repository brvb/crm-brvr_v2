<div id="pagination_wrapper" class="dataTables_wrapper">
    <div id="dataTables_pagination_info" class="dataTables_info" role="status">
        @if ($paginator->currentPage() < $paginator->lastPage())
            {{ __('Showing :initial to :final of :total entries', ['initial' => ($paginator->currentPage() - 1) * $paginator->perPage() + 1, 'final' => $paginator->currentPage() * $paginator->perPage(), 'total' => $paginator->total()]) }}
        @elseif ($paginator->total() == 0)
            {{ __('No entries to show.') }}
        @else
            {{ __('Showing :initial to :final of :total entries', ['initial' => ($paginator->currentPage() - 1) * $paginator->perPage() + 1, 'final' => $paginator->total(), 'total' => $paginator->total()]) }}
        @endif
    </div>
    <div class="dataTables_paginate paging_simple_numbers" id="dataTables_page_numbers">
        @if (!$paginator->onFirstPage())
            <a wire:click="previousPage" dusk="nextPage.after" class="paginate_button next">{{ __('Previous') }}</a>
        @endif
        @if ($paginator->total() != $paginator->currentPage())
            @foreach ($elements as $element)
                <span>
                    @if (is_string($element))
                        <li class="pagination-item disabled" >
                            * <a href="#">{{ $element }}</a>*
                        </li>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <a class="paginate_button current" data-dt-idx="{{ $page }}" tabindex="0" wire:click.prevent>{{ $page }}</a>
                            @else
                                <a class="paginate_button " id="page-{{ $page }}" data-dt-idx="{{ $page }}" tabindex="0" wire:click.prevent="gotoPage({{ $page }})">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                </span>
            @endforeach
        @else
            <a class="paginate_button current" data-dt-idx="{{ $paginator->currentPage() }}" tabindex="0" wire:click.prevent>{{ $paginator->currentPage() }}</a>
        @endif
        @if ($paginator->hasMorePages())
            <a wire:click="nextPage" dusk="nextPage.after" class="paginate_button next" data-dt-idx="{{ $page }}" tabindex="{{ $page }}">{{ __('Next') }}</a>
        @endif
    </div>
</div>
