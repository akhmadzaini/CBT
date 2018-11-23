VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
END
Attribute VB_Name = "Sheet1"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = True
Private Sub cmbSimpanUjian_Click()
    If (diSunting = False) Then
        Exit Sub
    End If
    Dim json As Object
    response = webService.request("sunting_ujian", "POST", serialize.settingUjian & _
    "&" & serialize.pesertaUjian)
    Set json = JsonConverter.ParseJson(response)
    If (json("pesan") = "ok") Then
        Range("B4").Value = json("ujian_id")
        If (json("status_soal") = "1") Then
            Range("B5").Value = "Sudah diunggah"
        Else
            Range("B5").Value = "Belum diunggah"
        End If
        pesan = MsgBox("Konfigurasi ujian telah diunggah dengan ID Soal " & json("ujian_id") & _
                Chr(13) & "Selanjutnya ID soal ini bisa digunakan untuk mengunggah soal (ms Word)" & _
                Chr(13) & "Simpan perubahan pada dokumen ini sebagai dokumentasi", vbOKOnly + vbInformation)
    ElseIf (json("pesan") = "terkunci") Then
        MsgBox "Tidak dapat mengunggah peserta ujian, dikarenakan soal sudah terkunci"
    ElseIf (json("pesan") = "login_gagal") Then
        MsgBox "Otentikasi webservice gagal dilakukan, mohon periksa LOGIN dan PASSWORD"
    ElseIf (json("pesan") = "ujian_tak_tersedia") Then
        MsgBox "Ujian dengan ID " & Range("B4").Value & " tak tersedia"
    Else
        MsgBox "Gagal terhubung ke webservice, galat tak terduga"
    End If
        
End Sub
Private Sub cmdUjianBaru_Click()
    Dim json As Object
    response = webService.request("ujian_baru", "POST", serialize.settingUjian & _
    "&" & serialize.pesertaUjian)
    Set json = JsonConverter.ParseJson(response)
    If (json("pesan") = "ok") Then
        Range("B4").Value = json("ujian_id")
        Range("B5").Value = "Belum diunggah"
        pesan = MsgBox("Konfigurasi ujian telah diunggah dengan ID Soal " & json("ujian_id") & _
                Chr(13) & "Selanjutnya ID soal ini bisa digunakan untuk mengunggah soal (ms Word) " & _
                Chr(13) & "Simpan perubahan pada dokumen ini sebagai dokumentasi", vbOKOnly + vbInformation)
    ElseIf (json("pesan") = "login_gagal") Then
        MsgBox "Otentikasi webservice gagal dilakukan, mohon periksa LOGIN dan PASSWORD"
    Else
        MsgBox "Gagal terhubung ke webservice, galat tak terduga"
    End If
                
End Sub
Private Function diSunting() As Boolean
    Dim konfirm As Integer
    konfirm = MsgBox("Tindakan ini akan menyebabkan pembaruan terhadap seluruh data peserta, " & _
    Chr(13) & "Anda yakin akan mengedit ujian " & Range("B4").Value & " ?", vbYesNo + vbExclamation)
    If (konfirm = vbYes) Then
        diSunting = True
    Else
        diSunting = False
    End If
End Function
