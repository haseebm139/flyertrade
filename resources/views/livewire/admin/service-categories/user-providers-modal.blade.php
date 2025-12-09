<div>
    {{-- @if ($show)
        <div class="modal " style="display: flex;" id="userModal" tabindex="-1" aria-labelledby="userModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered jusoio">
                <div class="modal-content  rounded-3" style="position:static">
                    <div class="modal-header border-0">
                        <h5 class="modal-title moddal" id="userModalLabel">{{ $title }}</h5>
                        <button type="button" wire:click="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div id="userList" class="list-group">

                            @forelse ($providers as $provider)
                                <div class="list-group-item d-flex align-items-center">
                                    <img src="{{ asset($provider->avatar ?? 'assets/images/icons/person-one.svg') }}" class="rounded-circle me-3"
                                        style="width:1.823vw; height:1.823vw; object-fit:cover;" alt="User">
                                    <div>
                                        <h6 class="mb-0" style="font-size:0.833vw;">{{ $provider->name }}</h6>
                                        <small class="text-muted" style="font-size:0.729vw;">{{ $provider->email ?? '' }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted text-center py-3">
                                    No providers found for this service.
                                </div>
                            @endforelse

                             
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif --}}

</div>
