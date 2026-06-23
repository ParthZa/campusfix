function validateComplaintForm()
{
    var title = document.getElementById("title").value;
    var description = document.getElementById("description").value;

    if(title.length < 5)
    {
        alert("Complaint title must be at least 5 characters");
        return false;
    }

    if(description.length < 10)
    {
        alert("Description must be at least 10 characters");
        return false;
    }

    return true;
}
function trackComplaint()
{
    var track_id = document.getElementById("track_id").value;

    $.ajax({
        url: "/campusfix/ajax/live_track.php",
        type: "POST",
        data: {track_id: track_id},
        success: function(response)
        {
            $("#tracking_result").html(response);
        }
    });
}
function toggleTheme()
{
    if(document.body.classList.contains("darkmode"))
    {
        document.body.classList.remove("darkmode");
        document.cookie = "theme=light; path=/; max-age=604800";
    }
    else
    {
        document.body.classList.add("darkmode");
        document.cookie = "theme=dark; path=/; max-age=604800";
    }
}

window.onload = function()
{
    let cookies = document.cookie.split(";");

    cookies.forEach(function(cookie){
        if(cookie.includes("theme=dark"))
        {
            document.body.classList.add("darkmode");
        }
    });
}