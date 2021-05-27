<section id="outOf" class="mx-3 m-lr-0 is-hidden">
    <div class="box radius-top-0">
        <div class="table-container">
            <table class="table is-hoverable is-fullwidth is-size-7">
                <thead>
                    <tr>
                        <th><abbr> # </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Category </abbr></th>
                        <th><abbr> Actions </abbr></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($outOfStockMerchandises as $product)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized">
                                {{ $product->name ?? 'N/A' }}
                                @if ($product->code)
                                    <span class="has-text-grey has-has-text-weight-bold">
                                        -
                                        {{ $product->code }}
                                    </span>
                                @endif
                            </td>
                            <td class="is-capitalized"> {{ $product->productCategory->name ?? 'N/A' }} </td>
                            <td>
                                <a href="{{ route('warehousesProducts', [$warehouse->id, $product->id]) }}" data-title="Modify Supplier Data">
                                    <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                        <span class="icon">
                                            <i class="fas fa-history"></i>
                                        </span>
                                        <span>
                                            History
                                        </span>
                                    </span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
