<!DOCTYPE html>
<html>
<head>
    <title>Camera Test</title>
</head>
<body>

<h2>Camera Test</h2>

<button onclick="testCamera()">
Test Camera
</button>

<script>
async function testCamera()
{
    try
    {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: true
        });

        alert("✅ Camera works!");
    }
    catch(error)
    {
        alert("❌ " + error.message);
    }
}
</script>

</body>
</html>