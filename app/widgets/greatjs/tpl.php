<script>
    (function () {

        const jsonStr = '<?= $paramsJson; ?>'
                .replace(/&quot;/g, '"')
                .replace(/&#039;/g, "'")
                .replace(/&amp;/g, '&')
                .replace(/&lt;/g, "<")
                .replace(/&gt;/g, ">");

        if (!jsonStr)
            return;
        const jsContent = JSON.parse(jsonStr);
        try {

            if (!jsContent.body)
                return;
            const args = jsContent.args !== undefined ? jsContent.args : [];
            const argsNames = jsContent.argsNames !== undefined ? jsContent.argsNames : [];
            const func = new Function(argsNames, jsContent.body);
            func.apply(null, args);

        } catch (e) {
            console.error(e)
        }
    }());
</script>