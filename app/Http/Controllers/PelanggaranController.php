<?php

namespace App\Http\Controllers;

use App\Pelanggaran;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
  public function index($limit = 10, $offset = 0)
  {
    $data["count"] = Pelanggaran::count();
    $pelanggaran = array();

    foreach (Pelanggaran::take($limit)->skip($offset)->get() as $p) {
        $item = [
            "id"                => $p->id,
            "nama_pelanggaran"  => $p->nama_pelanggaran,
            "kategori"          => $p->kategori,
            "poin"              => $p->poin,
        ];

        array_push($pelanggaran, $item);
    }
    $data["pelanggaran"] = $pelanggaran;
    $data["status"] = 1;
    return response($data);
  }

  public function store(Request $request)
  {
      $pelanggaran = new Pelanggaran([
        'nama_pelanggaran' => $request->nama_pelanggaran,
        'kategori' => $request->kategori,
        'poin' => $request->poin,
      ]);

      $pelanggaran->save();
      return response()->json([
        'status'  => '1',
        'message' => 'Data pelanggaran berhasil ditambahkan!'
      ]);

  }

  public function show($id)
  {
      $pelanggaran = Pelanggaran::where('id', $id)->get();

      $data_pelanggaran = array();
      foreach ($pelanggaran as $p) {
          $item = [
            "id"                => $p->id,
            "nama_pelanggaran"  => $p->nama_pelanggaran,
            "kategori"          => $p->kategori,
            "poin"              => $p->poin,
          ];
          array_push($data_pelanggaran, $item);
      }

      $data["dataPelanggaran"] = $data_pelanggaran;
      $data["status"] = 1;
      return response($data);

  }


  public function update($id, Request $request)
  {
      $pelanggaran = Pelanggaran::where('id', $id)->first();

      $pelanggaran->nama_pelanggaran = $request->nama_pelanggaran;
      $pelanggaran->kategori = $request->kategori;
      $pelanggaran->poin = $request->poin;
      $pelanggaran->updated_at = now()->timestamp;

      $pelanggaran->save();

      return response()->json([
        'status'  => '1',
        'message' => 'Data pelanggaran berhasil diubah.'
      ]);
  }

  public function destroy($id)
  {
      $pelanggaran = Pelanggaran::where('id', $id)->first();

      $pelanggaran->delete();

      return response()->json([
        'status'  => '1',
        'message' => 'Data pelanggaran berhasil dihapus.'
      ]);
  }
}
