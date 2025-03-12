@props(['message' => 'Good bye...'])

<div>
    <div id="farewellModal">
        <div class="farewellModal modal-content">
            <div class="icon-container">
                <animated-icons
                    src="https://animatedicons.co/get-icon?name=Hey&style=minimalistic&token=a91f6990-968c-4b27-8d19-1e6965a394aa"
                    trigger="loop"
                    attributes='{"variationThumbColour":"#FFFFFF","variationName":"Normal","variationNumber":1,"numberOfGroups":1,
                                "backgroundIsGroup":false,"strokeWidth":1,"defaultColours":{"group-1":"#FFFFFF","background":"#3394fb"}}'
                    height="50"
                    width="50"
                ></animated-icons>
            </div>
            <p class="farewellModal modal-text">{{ $message }}</p>
        </div>
    </div>
</div>