var expression = "";

    function show(value)
    {
        expression += value;
        document.getElementById("display").value = expression;
    }

    function clearDisplay()
    {
        expression = "";
        document.getElementById("display").value = "0";
    }

    function calculate()
    {
        if (expression == "")
        {
            alert("Please enter an expression");
            return;
        }

        var result = eval(expression);
        document.getElementById("display").value = result;
        expression = "";
    }