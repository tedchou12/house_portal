function ConvToObject(data)
{
    if (typeof data == 'string') {
        try {
            data = JSON.stringify(data);
        } catch (e) {
            data = null;
        }
    } else if (typeof data != 'object') {
        data = null;
    }

    return data;
}

function IsValidFeedback(feedback)
{
    return (typeof feedback == 'object') && ('success' in feedback) && ('failure' in feedback);
}


    function ConvToObject(data)
    {
        if (typeof data == 'string') {
            try {
                data = JSON.stringify(data);
            } catch (e) {
                data = null;
            }
        } else if (typeof data != 'object') {
            data = null;
        }

        return data;
    }

    function IsValidFeedback(feedback)
    {
        return (typeof feedback == 'object') && ('success' in feedback) && ('failure' in feedback);
    }

    function OpenRefreshBlock()
    {
        $.blockUI({
            message: $('#block_refresh'),
            css: {
                background: 'none',
                border: 'none',
            }
        });
    }

    function CloseBlock()
    {
        $.unblockUI();
    }
