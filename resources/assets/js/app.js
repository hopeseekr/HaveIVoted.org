
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
window.$ = window.jQuery = require('jquery');

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

$(document).ready(function () {
    // $('#county').editableSelect();

    $('.btn-search').click(function () {
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

        $("body").css("cursor", "progress");
        $.post('/api/voters/' + state + '/', payload)
            .done(function (response) {
                const $voterResults = $('.voter-results');
                $voterResults.show();
                // $voterResults.html(JSON.stringify(response.data));

                const $votersTable = $('#votersTable');
                const $voterDetails = $('#votersTable .voter-details:first');

                $("#votersTable > tbody").html('');

                $.each(response.data, function (index, voter) {
                    const $newVoterDetails = $voterDetails.clone();
                    $newVoterDetails.find('.last_name').html(voter.last_name);
                    $newVoterDetails.find('.given_names').html(voter.given_names);
                    $newVoterDetails.find('.county').html(voter.county);
                    $newVoterDetails.find('.precinct').html(voter.precinct);
                    $newVoterDetails.find('.recorded_on').html(voter.recorded_on);

                    $votersTable.append($newVoterDetails);
                })
            })
            .fail(function (data) {
                var response = JSON.parse(data.responseText);

                showErrors(response.errors);
            })
            .always(function () {
                $("body").css("cursor", "default");
            })
    });
});
