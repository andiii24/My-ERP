<section id="limited" class="mx-3 m-lr-0 is-hidden">
    <div class="box radius-top-0">
        <div class="table-container">
            <table class="table is-hoverable is-fullwidth is-size-7">
                <thead>
                    <tr>
                        <th><abbr> # </abbr></th>
                        <th><abbr> Product </abbr></th>
                        <th><abbr> Code </abbr></th>
                        <th><abbr> Warehouse </abbr></th>
                        <th class="has-text-right"><abbr> Quantity Received </abbr></th>
                        <th class="has-text-right text-green"><abbr> On Hand </abbr></th>
                        <th class="has-text-right text-purple"><abbr> Damaged </abbr></th>
                        <th class="has-text-right text-blue"><abbr> Sold </abbr></th>
                        <th class="has-text-right text-gold"><abbr> Returns </abbr></th>
                        <th class="has-text-right"><abbr> Added On </abbr></th>
                        <th class="has-text-right"><abbr> Expiry Date </abbr></th>
                        @can('delete', $limitedMerchandises->first())
                            <th><abbr> Added By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                        @endcan
                        <th class="has-text-centered"><abbr> Actions </abbr></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($limitedMerchandises as $merchandise)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized"> {{ $merchandise->product->name ?? 'N/A' }} </td>
                            <td class="is-capitalized"> {{ $merchandise->product->code ?? 'N/A' }} </td>
                            <td class="is-capitalized"> {{ $merchandise->warehouse->name ?? 'N/A' }} </td>
                            <td class="has-text-right">
                                {{ $merchandise->total_received }}
                                {{ $merchandise->product->unit_of_measurement }}
                            </td>
                            <td class="has-text-right">
                                <span class="tag is-small bg-green has-text-white">
                                    {{ $merchandise->total_on_hand }}
                                    {{ $merchandise->product->unit_of_measurement }}
                                </span>
                            </td>
                            <td class="has-text-right">
                                <span class="tag is-small bg-purple has-text-white">
                                    {{ $merchandise->total_broken }}
                                    {{ $merchandise->product->unit_of_measurement }}
                                </span>
                            </td>
                            <td class="has-text-right">
                                <span class="tag is-small bg-blue has-text-white">
                                    {{ $merchandise->total_sold }}
                                    {{ $merchandise->product->unit_of_measurement }}
                                </span>
                            </td>
                            <td class="has-text-right">
                                <span class="tag is-small bg-gold has-text-white">
                                    {{ $merchandise->total_returns }}/{{ $merchandise->total_sold }}
                                    {{ $merchandise->product->unit_of_measurement }}
                                </span>
                            </td>
                            <td class="has-text-right"> {{ $merchandise->created_at->toFormattedDateString() }} </td>
                            <td class="has-text-right"> {{ $merchandise->expires_on ? $merchandise->expires_on->toFormattedDateString() : 'N/A' }} </td>
                            @can('delete', $merchandise)
                                <td> {{ $merchandise->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $merchandise->updatedBy->name ?? 'N/A' }} </td>
                            @endcan
                            <td class="has-text-centered">
                                <a href="{{ route('merchandises.edit', $merchandise->id) }}" data-title="Manage Returned or Damaged Product">
                                    <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                        <span class="icon">
                                            <i class="fas fa-toolbox"></i>
                                        </span>
                                        <span>
                                            Manage
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
