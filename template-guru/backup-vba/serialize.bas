Attribute VB_Name = "serialize"
' Class khusus untuk konversi data dari excel
' menjadi parameter request webservice
Function jawaban() As String
    Dim hasil As String
    Dim cell As String
    For n = 1 To 10
        cell = "C" & Trim(Str(n + 6))
        If (Trim(Sheet1.Range(cell).Value) <> "") Then
            hasil = hasil & "&jwb" & Trim(Str(n)) & "=" + Sheet1.Range(cell).Value
        End If
    Next n
    jawaban = hasil
End Function
Function settingUjian() As String
    Dim ujianId As String
    Dim tglUjian As String
    Dim alokasi As String
    Dim jumlahSoal As String
    Dim acak As String
    
    ' Mengumpulkan data
    tglUjian = Sheet1.Range("F9").Value & "-" & Sheet1.Range("D9").Value & "-" & _
                Sheet1.Range("B9").Value & " " & Sheet1.Range("B10").Value & ":" & _
                Sheet1.Range("D10").Value
    alokasi = Sheet1.Range("B11").Value
    jumlahSoal = Sheet1.Range("B12").Value
    acak = Sheet1.Range("B13").Value
    ujianId = Sheet1.Range("B4").Value
    
    ' Memformat data
    settingUjian = "ujian_id=" & ujianId & "&mulai=" & tglUjian & "&alokasi=" & alokasi & _
                    "&jml_soal=" & jumlahSoal & "&acak=" & acak
End Function
Function pesertaUjian() As String
    Dim hasil As String
    Dim temp As String
    Dim cell As String
    Dim peserta As String
    Dim n As Integer
    
    hasil = "["
    n = 5
    cell = "H" & n
    
    While Sheet1.Range(cell).Value <> ""
        temp = "{" & Chr(34) & "nis" & Chr(34) & ":" + Chr(34) & Sheet1.Range("H" & n).Value & Chr(34) & ","
        temp = temp & Chr(34) & "login" & Chr(34) & ":" + Chr(34) & Sheet1.Range("I" & n).Value & Chr(34) & ","
        temp = temp & Chr(34) & "nama" & Chr(34) & ":" + Chr(34) & Sheet1.Range("J" & n).Value & Chr(34) & ","
        temp = temp & Chr(34) & "password" & Chr(34) & ":" + Chr(34) & Sheet1.Range("K" & n).Value & Chr(34) & ","
        temp = temp & Chr(34) & "sesi" & Chr(34) & ":" + Chr(34) & Sheet1.Range("L" & n).Value & Chr(34) & ","
        temp = temp & Chr(34) & "server" & Chr(34) & ":" + Chr(34) & Sheet1.Range("M" & n).Value & Chr(34) & ","
        temp = temp & Chr(34) & "kelas" & Chr(34) & ":" + Chr(34) & Sheet1.Range("N" & n).Value & Chr(34) & "}"
        hasil = hasil & temp
        n = n + 1
        cell = "H" & n
        If (Sheet1.Range(cell).Value <> "") Then
            hasil = hasil & ","
        End If
    Wend
    
    hasil = hasil & "]"
    pesertaUjian = "peserta=" & hasil
End Function

