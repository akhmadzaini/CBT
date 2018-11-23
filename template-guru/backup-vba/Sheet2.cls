VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Sheet2"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = True
Private Sub cmdResetSiswa_Click()
    Dim data As String
    Dim json As Object
    Dim response As String
    data = "username=" & txtResetLogin.Text & "&ujian_id=" & Sheet1.Range("B1").Value
    response = webService.request("reset_status_login_siswa", "POST", data)
    Set json = JsonConverter.ParseJson(response)
    If (json("pesan") = "ok") Then
        MsgBox "Siswa dengan username '" & txtResetLogin.Text & "' telah direset"
    End If
End Sub
