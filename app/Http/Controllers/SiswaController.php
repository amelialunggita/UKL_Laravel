<?php

namespace App\Http\Controllers;

use App\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
  public function index($limit = 10, $offset = 0)
  {
    $data["count"] = Siswa::count();
    $siswa = array();

    foreach (Siswa::take($limit)->skip($offset)->get() as $p) {
        $item = [
            "id"                => $p->id,
            "nis"               => $p->nis,
            "nama_siswa"        => $p->nama_siswa,
            "kelas"             => $p->kelas,
            "created_at"        => now(),    
            "updated_at"        => now(),    

            // "poin"              => $p->poins,
        ];

        array_push($siswa, $item);
    }
    $data["siswa"] = $siswa;
    $data["status"] = 1;
    return response($data);
  }

  public function store(Request $request)
  {
      $siswa = new Siswa([
        'nis'         => $request->nis,
        'nama_siswa'  => $request->nama_siswa,
        'kelas'       => $request->kelas,
      ]);

      $siswa->save();
      return response()->json([
        'status'  => '1',
        'message' => 'Data siswa berhasil ditambahkan.'
      ]);

  }

  public function show($id)
  {
      $siswa = Siswa::where('id', $id)->get();

      $dataSiswa = array();
      foreach ($siswa as $p) {
          $item = [
            "id"           => $p->id,
            "nis"          => $p->nis,
            "nama_siswa"   => $p->nama_siswa,
            "kelas"        => $p->kelas,
            "poin"         => $p->poins,
          ];
          array_push($dataSiswa, $item);
      }

      $data["dataSiswa"] = $dataSiswa;
      $data["status"] = 1;
      return response($data);

  }

//   public function index($limit = 10, $offset = 0)
//   {
//     $find = $reqest->find;
//     $siswas = Siswa::where("kelas", "like", "%$find%");
//     $siswa = array();

//     foreach ($siswas->take($limit)->skip($offset)->get() as $p) {
//         $item = [
//             "nis"               => $p->nis,
//             "nama_siswa"        => $p->nama_siswa,
//             "kelas"             => $p->kelas,
//             "poin"              => $p->poins,
//         ];

//         array_push($siswa, $item);
//     }
//     $data["siswa"] = $siswa;
//     $data["status"] = 1;
//     return response($data);
//   }

  public function update($id, Request $request)
  {
      $siswa = Siswa::where('id', $id)->first();

      $siswa->nis           = $request->nis;
      $siswa->nama_siswa    = $request->nama_siswa;
      $siswa->kelas         = $request->kelas;
      $siswa->updated_at    = now()->timestamp;

      $siswa->save();

      return response()->json([
        'status'  => '1',
        'message' => 'Data siswa berhasil diubah.'
      ]);
  }

  public function destroy($id)
  {
      $siswa = Siswa::where('id', $id)->first();

      $siswa->delete();

      return response()->json([
        'status'  => '1',
        'message' => 'Delete siswa berhasil dihapus.'
      ]);
  }
}
