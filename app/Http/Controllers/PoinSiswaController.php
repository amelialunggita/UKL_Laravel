<?php

namespace App\Http\Controllers;

use App\Poin_Siswa;
use App\Pelanggaran;
use Illuminate\Http\Request;

class PoinSiswaController extends Controller
{
    public function index($limit = 10, $offset = 0)
    {
      $data["count"] = Poin_Siswa::count();
      $poin = array();

      foreach (Poin_Siswa::take($limit)->skip($offset)->get() as $p) {
          $item = [
              "id"                          => $p->id,
              "nama_siswa"                  => $p->siswas->nama_siswa,
              "kelas"                       => $p->siswas->kelas,
              "nis"                         => $p->siswas->nis,
              "nama_pelanggaran"            => $p->pelanggarans->nama_pelanggaran,
              "kategori"                    => $p->pelanggarans->kategori,
              "poin"                        => $p->pelanggarans->poin,
              "tanggal"                     => $p->tanggal,
              //   "id_siswa"               => $p->id_siswa,

          ];

          array_push($poin, $item);
      }
      $data["poin"] = $poin;
      $data["status"] = 1;
      return response($data);
    //   return Poin_Siswa::all();
    }

    public function store(Request $request)
    {
        $poin_siswa = new Poin_Siswa([
          'id_siswa'        => $request->id_siswa,
          'id_pelanggaran'  => $request->id_pelanggaran,
          'tanggal'         => now(),
          'keterangan'      => $request->keterangan,
        ]);

        $poin_siswa->save();
        return response()->json([
            'status'  => '1',
            'message' => 'Data poin pelanggaran berhasil ditambahkan!'
          ]);

    }

    public function show($id)
    {
        $poin = Poin_Siswa::where('id', $id)->get();

        $poin_siswa = array();
        foreach ($poin as $p) {
            $item = [
                "id"              => $p->id,
                "id_siswa"        => $p->id_siswa,
                "id_pelanggaran"  => $p->id_pelanggaran,
                "tanggal"         => $p->tanggal,
                "keterangan"      => $p->keterangan,
                "poin_siswa"      => $p->pelanggarans->poin,
                "kategori"        => $p->pelanggarans->kategori,
            ];
            array_push($poin_siswa, $item);
        }

        $data["poinSiswa"] = $poin_siswa;
        $data["status"] = 1;
        return response($data);

    }

    public function detail($id)
    {
        $poin = Poin_Siswa::where('id_siswa', $id)->get();

        $total = 0;
        $poin_siswa = array();
        foreach ($poin as $p) {
            
            $total += $p->pelanggarans->poin;
            $item = [
                "id"              => $p->id,
                "id_siswa"        => $p->id_siswa,
                "id_pelanggaran"  => $p->id_pelanggaran,
                "tanggal"         => $p->tanggal,
                "keterangan"      => $p->keterangan,
                "poin_siswa"      => $p->pelanggarans->poin,
                "kategori"        => $p->pelanggarans->kategori,
            ];
            array_push($poin_siswa, $item);
        }

        $data['total'] = $total;
        $data["poinSiswa"] = $poin_siswa;
        $data["status"] = 1;
        return response($data);

    }


    public function update($id, Request $request)
    {
        $poin = Poin_Siswa::where('id', $id)->first();

        $poin->id_siswa = $request->id_siswa;
        $poin->id_pelanggaran = $request->id_pelanggaran;
        $poin->keterangan = $request->keterangan;
        $poin->updated_at = now()->timestamp;

        $poin->save();

        return response()->json([
            'status'  => '1',
            'message' => 'Data poin berhasil diubah.'
          ]);    }

    public function destroy($id)
    {
        $poin = Poin_Siswa::where('id', $id)->first();

        $poin->delete();

        return response()->json([
          'status'  => '1',
          'message' => 'Data poin pelanggaran berhasil dihapus.'
        ]);
    }

    public function find(Request $request, $limit = 10, $offset = 0)
    {
        $find = $request->find;
        $dataPoinSiswa = Poin_siswa::with('pelanggarans')
        ->whereHas('siswas',function ($query) use ($find){
            $query->where("nama_siswa", "like", "%$find%");});
  
        $detail = array();
  
        foreach ($dataPoinSiswa->get() as $p) {
            $item = [
                "tanggal"           => $p->tanggal,
                "nama_pelanggaran"  => $p->pelanggarans->nama_pelanggaran,
                "kategori"          => $p->pelanggarans->kategori,
                "poin"              => $p->pelanggarans->poin,
            ];
  
            array_push($detail, $item);
        }
        $data           = $dataPoinSiswa->first();
        $nama_siswa     = $data->siswas->nama_siswa;
        $nis            = $data->siswas->nis;
        $kelas          = $data->siswas->kelas;
        $status         = 1;
        return response()->json(compact('nama_siswa','nis','kelas','detail'));
    } 
}
