<section id="reserved" class="mx-3 m-lr-0 is-hidden">
    <div class="box radius-top-0">
        <div>
            <table id="table2" class="is-hoverable is-size-7 display nowrap" data-date="[]" data-numeric="[{{ implode(',', range(3, $warehouses->count() + 3)) }}]">
                <thead>
                    <tr>
                        <th id="firstTarget"><abbr> # </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Category </abbr></th>
                        @foreach ($warehouses as $warehouse)
                            <th class="has-text-right text-green is-capitalized"><abbr> {{ $warehouse->name }} </abbr></th>
                        @endforeach
                        <th class="has-text-right text-green is-capitalized"><abbr> Total Reserved </abbr></th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach ($reservedMerchandiseProducts as $product)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized name">
                                {{ $product->name ?? 'N/A' }}
                                @if ($product->code)
                                    <span class="has-text-grey has-has-text-weight-bold">
                                        -
                                        {{ $product->code }}
                                    </span>
                                @endif
                            </td>
                            <td class="is-capitalized"> {{ $product->productCategory->name ?? 'N/A' }} </td>
                            @foreach ($warehouses as $warehouse)
                                <td class="has-text-right">
                                    <a href="{{ route('warehouses-products', [$warehouse->id, $product->id]) }}" data-title="View Product History">
                                        <span class="tag is-small btn-green is-outline">
                                            {{ $merchandise->getProductReservedInWarehouse($reservedMerchandises, $product->id, $warehouse->id) }}
                                            {{ $product->unit_of_measurement }}
                                        </span>
                                    </a>
                                </td>
                            @endforeach
                            <td class="has-text-right">
                                <span class="tag is-small bg-green has-text-white">
                                    {{ $merchandise->getProductReservedTotalBalance($reservedMerchandises, $product->id) }}
                                    {{ $product->unit_of_measurement }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
