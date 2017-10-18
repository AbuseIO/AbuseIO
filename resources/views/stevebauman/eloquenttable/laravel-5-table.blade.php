{{-- Let's start generating the table for the collection, first we'll assign the table attributes --}}
<table @if($collection->eloquentTableAttributes) {!! $collection->eloquentTableAttributes !!} @else class="table table-striped" @endif>
    <thead>
    <tr>
        {{-- Now, let's go through every column and output it's header --}}
        @foreach($collection->eloquentTableColumns as $key => $name)
            {{-- We'll apply the hidden column attributes if the column is meant to be hidden --}}
            <th {!! $collection->getHiddenColumnAttributes($key) !!}>
                @if(in_array($key, $collection->eloquentTableSort))
                    {{-- If the header key is inside the table sort array, we'll output the sorted link --}}
                    {!! sortableUrlLink($name, array('field' => $key, 'sort'=>'asc')) !!}
                @elseif(array_key_exists($key, $collection->eloquentTableSort))
                    {{-- If the header key is a key inside of the table sort array, we'll output the sorted link --}}
                    {!! sortableUrlLink($name, array('field' => $collection->eloquentTableSort[$key], 'sort'=>'asc')) !!}
                @else
                    {{-- Looks like we don't have any header modifications, we'll just output the name --}}
                    {{ ucfirst($name) }}
                @endif
            </th>
        @endforeach
    </tr>
    </thead>

    <tbody>
    {{-- Let's get started going through the actual collection records and outputting the data --}}
    @foreach($collection as $record)
        <tr {!! $collection->getRowAttributes($record) !!}>
            {{-- We'll loop through every column we were given --}}
            @foreach($collection->eloquentTableColumns as $key => $name)
                {{-- Make sure we apply the hidden column attributes to the table data if it's meant to be hidden with the modifications --}}
                <td {!! $collection->getCellAttributes($key,$record) !!}>
                    {{-- If the column key exists in the table means array, we're outputting a relationship value --}}
                    @if(array_key_exists($key, $collection->eloquentTableMeans))
                        {{-- If the column key also exists in the table modifications array, we're also modifying the relationship object --}}
                        @if(array_key_exists($key, $collection->eloquentTableModifications))
                            {!!
                                call_user_func_array($collection->eloquentTableModifications[$key], array(
                                    $record->getRelationshipObject($collection->eloquentTableMeans[$key]), $record
                                ))
                            !!}
                        @else
                            {{-- The column key doesn't exist inside the modifications array, we're just outputting the relationship value --}}
                            {!! $record->getRelationshipProperty($collection->eloquentTableMeans[$key]) !!}
                        @endif
                    @else
                        {{-- So the column isn't a relationship, let's see if we have to modify the value before displaying it --}}
                        @if(array_key_exists($key, $collection->eloquentTableModifications))
                            {!! call_user_func_array($collection->eloquentTableModifications[$key], array($record)) !!}
                        @else
                            {{-- We don't need to modify the value, let's see if the property exists with the column key first --}}
                            @if($record->{$key})
                                {{-- Great, the key exists on the record, we'll output it here --}}
                                {!! $record->{$key}  !!}
                            @else
                                {{-- Looks like the column key isn't a valid key, we'll try outputting with the column name --}}
                                {!! $record->{$name}  !!}
                            @endif
                        @endif
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
