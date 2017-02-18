@extends('header')

@section('content')


    <center>
        @if (!session(SESSION_USER_ACCOUNTS) || count(session(SESSION_USER_ACCOUNTS)) < 5)
            {!! Button::success(trans('texts.add_company'))->asLinkTo(url('/invoice_now?new_company=true&sign_up=true')) !!}
        @endif
    </center>

    <p>&nbsp;</p>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-striped">
                        @foreach (Session::get(SESSION_USER_ACCOUNTS) as $company)
                            <tr>
                                <td>
                                    @if (isset($company->logo_url))
                                        {!! HTML::image($company->logo_url.'?no_cache='.time(), 'Logo', ['width' => 100]) !!}
                                    @endif
                                </td>
                                <td>
                                    <h3>{{ $company->company_name }}<br/>
                                        <small>{{ $company->user_name }}
                                        </small>
                                    </h3>
                                </td>
                                <td>
                                    @if ($company->user_id == Auth::user()->id)
                                        <b>{{ trans('texts.logged_in')}}</b>
                                    @else
                                        {!! Button::primary(trans('texts.unlink'))->withAttributes(['onclick'=>"return showUnlink({$company->id}, {$company->user_id})"]) !!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="unlinkModal" tabindex="-1" role="dialog" aria-labelledby="unlinkModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('texts.unlink_company') }}</h4>
                </div>

                <div class="container">
                    <h3>{{ trans('texts.are_you_sure') }}</h3>
                </div>

                <div class="modal-footer" id="signUpFooter">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{ trans('texts.cancel') }}</button>
                    <button type="button" class="btn btn-primary"
                            onclick="unlinkCompany()">{{ trans('texts.unlink') }}</button>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function showUnlink(userCompanyId, userId) {
            NINJA.unlink = {
                'userCompanyId': userCompanyId,
                'userId': userId
            };
            $('#unlinkModal').modal('show');
            return false;
        }

        function unlinkCompany() {
            window.location = '{{ URL::to('/unlink_company') }}' + '/' + NINJA.unlink.userCompanyId + '/' + NINJA.unlink.userId;
        }

    </script>

@stop
