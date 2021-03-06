
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
window.$ = window.jQuery = require('jquery');

let voterPageNum = 1;
let voterPageCount = 1;

function showErrors(errors)
{
    // @see https://stackoverflow.com/a/21654389/430062
    let errorString = '<h4>Invalid submission:</h4><ul>';
    $.each(errors, function(key, value) {
        errorString += '<li>' + value + '</li>';
    });
    errorString += '</ul>';
    $('.supported-states-box .errors').html(errorString);
}

function clearErrors()
{
    $('.supported-states-box .errors').html('');
}

function fetchVoters(pageNum)
{
    voterPageNum = pageNum;
    const state = $('input#state').val();
    const lastName = $('input#lastName').val();
    const givenNames = $('input#givenNames').val();

    let errors = [];
    clearErrors();

    if (!state) {
        errors.push('State is required.');
    }
    if (!lastName) {
        errors.push('Last Name is required.');
    }
    if (!givenNames) {
        errors.push('First Name is required.');
    }

    if (errors.length > 0) {
        //alert(errors.join("\n"));
        showErrors(errors);

        return false;
    }

    const payload = {
        // This shouldn't be hardcded
        lastName: lastName.toUpperCase(),
        givenNames: givenNames.toUpperCase()
    };

    const $votersTable = $('#votersTable');
    const $voterDetails = $('#votersTable .voter-details:first').clone();
    const $voterResults = $('.voter-results');

    $votersTable.show();
    $("#votersTable > tbody").html('');
    $votersTable.append($voterDetails);
    /**********************************************************************
     *
     *  ccccc ccccc
     *   ccc   ccc
     *    c     cccc
     *     ccccc     c
     *      ccccc   ccc
     *       ccccc ccccc
     *
     */
    $("body").css("cursor", "progress");
    $('#votersNav .current a').text(voterPageNum);
    $voterResults.find('#voterCount').text('');

    $.post('/api/voters/' + state + '/?page=' + pageNum, payload)
        .done(function (response) {
            $voterResults.show();
            // $voterResults.html(JSON.stringify(response.data));

            voterPageCount = response.last_page;

            $.each(response.data, function (index, voter) {
                const $newVoterDetails = $voterDetails.clone();
                $newVoterDetails.find('.last_name').html(voter.last_name);
                $newVoterDetails.find('.given_names').html(voter.given_names);
                $newVoterDetails.find('.county').html(voter.county);
                $newVoterDetails.find('.state').html(voter.state);
                $newVoterDetails.find('.precinct').html(voter.precinct);
                $newVoterDetails.find('.recorded_on').html(voter.recorded_on);

                $votersTable.append($newVoterDetails);
            });

            $voterResults.find('#voterCount').text(response.total);
        })
        .fail(function (data) {
            var response = JSON.parse(data.responseText);

            showErrors(response.errors);
        })
        .always(function () {
            $("body").css("cursor", "default");
        })
}

$(document).ready(function () {
    // $('#county').editableSelect();

    $('input#givenNames').keypress(function (e) {
        const keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode === 13) {
            fetchVoters(1);
        }
    });

    $('.btn-search').click(function () {
        fetchVoters(1);
    });

    $('#votersNav .prev a').click(function () {
        if (voterPageNum > 1) {
            fetchVoters(voterPageNum - 1);
        }
    });

    $('#votersNav .next a').click(function () {
        if (voterPageNum < voterPageCount) {
            fetchVoters(voterPageNum + 1);
        }
    });
});
