@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="supported-states-box">
            <h3>Only the State of Texas is supported as of now.</h3>
            <div class="errors"></div>
        </div>
        <form>
            <input type="hidden" id="state" value="TX" />
            <div class="inner-form voter-search">
{{--                <div class="input-field first-wrap" style="margin-right: 15px"  >--}}
{{--                    <div class="input-select">--}}
{{--                        <div class="autocomplete" style="width:300px;">--}}
{{--                            <select id="county">--}}
{{--                            @foreach ($counties as $countyId => $county)--}}
{{--                                <option selected="selected" disabled="disabled">County</option>--}}
{{--                                <option value="{{$countyId}}">{{$county}}</option>--}}
{{--                            @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="input-field second-wrap" style="margin-right: 15px">
                    <input id="lastName" type="text" placeholder="Last Name" />
                </div>
                <div class="input-field second-wrap" style="margin-right: 15px">
                    <input id="givenNames" type="text" placeholder="First and Middle Names" />
                </div>
                <div class="input-field third-wrap">
                    <button class="btn-search" type="button">
                        <svg class="svg-inline--fa fa-search fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="voter-results">
        <a href="#voter-results"></a>
        <div id="votersBox">
            <table id="votersTable">
                <thead>
                    <tr>
                        <th>Last Name</th>
                        <th>First Name(s)</th>
                        <th>County</th>
                        <th>Precinct</th>
                        <th>Recorded on</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="voter-details">
                        <td class="last_name"></td>
                        <td class="given_names"></td>
                        <td class="county"></td>
                        <td class="precinct"></td>
                        <td class="recorded_on"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <nav id="votersNav" aria-label="">
            <ul class="pagination">
                <li class="page-item prev">
                    <a class="page-link" href="#voter-results" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <li class="page-item current"><a class="page-link" href="#voter-results">1</a></li>
                <li class="page-item next">
                    <a class="page-link" href="#voter-results" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
            <div id="totalVoters"><strong>Total:</strong> <span id="voterCount"></span></div>
        </nav>
    </div>

@endsection
