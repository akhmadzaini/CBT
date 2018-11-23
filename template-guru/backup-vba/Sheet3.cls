VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Sheet3"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = True
Private Sub cmbTarikNilai_Click()
    'bersihkan data lama yang tidak terpakai
    Range("A4", "DB1048576").Value = ""
    Dim json As Object
    
    'ambil data ujian
    Dim idUjian As String
    idUjian = Sheet1.Range("B4").Value
    response = webService.request("tarik_nilai", "POST", "ujian_id=" & idUjian)
    
    Set json = JsonConverter.ParseJson(response)
    
    
    If (json("pesan") = "login_gagal") Then
        MsgBox "Otentikasi webservice gagal dilakukan, mohon periksa LOGIN dan PASSWORD"
        Exit Sub
    ElseIf (json("pesan") = "ujian_tak_tersedia") Then
        MsgBox "Ujian dengan ID " & Sheet1.Range("B4").Value & " tak tersedia"
        Exit Sub
    End If
    
    'render json ke cell
    Dim Values As Variant
    ReDim Values(json("data").Count, 106)
    
    Dim i As Long
    
    i = 0
    For Each Value In json("data")
      Values(i, 0) = i + 1
      Values(i, 1) = Value("nis")
      Values(i, 2) = Value("nama")
      Values(i, 3) = Value("last_login")
      Values(i, 4) = Value("nilai")
      Values(i, 5) = Value("status")
      For n = 1 To 100
        Values(i, 5 + n) = Value("no_" & Trim(Str(n)))
      Next n
      i = i + 1
    Next Value
    Range(Cells(4, 1), Cells((json("data").Count + 3), 106)) = Values
        
End Sub
