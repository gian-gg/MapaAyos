<?php

function renderToasts()
{
    echo "
    <div class='toast-container position-fixed bottom-0 end-0 p-3'>
        <div id='ma-toast' class='toast' role='alert' aria-live='assertive' aria-atomic='true'>
            <div class='toast-header'>
                <strong class='me-auto' id='ma-toast-title'>TOAST TITLE</strong>
                <button type='button' class='btn-close' data-bs-dismiss='toast' aria-label='Close'></button>
            </div>
            <div class='toast-body' id='ma-toast-body'>
                TOAST MESSAGE
            </div>
        </div>
    </div>
    ";
}
