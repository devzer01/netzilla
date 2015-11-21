{foreach from=$history item=msg}
        	{if $msg.type eq "inbox"}
            <!--receive -->
            <div class="container-chat-receive">
                
                    <img src="{$smarty.const.URL_WEB}/thumbnails.php?file={$rcpt.picturepath}&h=60" height="60" width="60" />
                    <div class="container-receive-content">
                        <span class="receive-name">{$rcpt.username|username} [{$msg.datetime}]</span>
                        <div class="container-receive-message">
                            <p class="receive-message">
                                {$msg.message|smiley} {$msg.gift_id|gift}
                            </p>
                            <div class="icon-message-left"></div>
                        </div>
                    </div>
                
            </div>
            <!--end receive -->
            {else}
            <!--send -->
            <div class="container-chat-send">
                <span class="send-name">[{$msg.datetime}]</span>
                <div class="container-send-message">
                    <p class="send-message">
                        {$msg.message|smiley} {$msg.gift_id|gift}
                    </p>
                    <div class="icon-message-right"></div>
                </div>
            </div>
            <!--end send -->
            {/if}
            {/foreach}