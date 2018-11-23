Attribute VB_Name = "webService"
' Module khusus yang didesain untuk mengakses
' webservice berbasis json
Property Get baseUrl() As String
    baseUrl = Sheet1.Range("B6").Value
End Property
Property Get login() As String
    login = Sheet1.Range("B7").Value
End Property
Property Get password() As String
    password = Sheet1.Range("B8").Value
End Property
Function request(segmen As String, method As String, data As String) As String
    Dim MyRequest As Object
    Dim url As String
    Dim auth As String
    data = "login=" & login & "&password=" & password & "&" & data
    url = baseUrl & "&m=" & segmen
    Set MyRequest = CreateObject("WinHttp.WinHttpRequest.5.1")
    MyRequest.Open method, url, False
    MyRequest.setRequestHeader "User-Agent", "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)"
    MyRequest.setRequestHeader "Content-type", "application/x-www-form-urlencoded"
    MyRequest.send (data)
    request = Trim(MyRequest.responseText)
End Function

